import asyncio
import logging
import cv2
import time
import os
import shutil
from datetime import datetime, time as dt_time
from bot.capturer import PremierPlusCapturer
from bot.telegram_api import PremierPlusNotifier
from config.settings import Settings
from bot.confirmacion import ValidadorImagenes
import tkinter as tk
from tkinter import ttk, messagebox
import threading

# Configuración de logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class PremierPlusMonitor:
    def __init__(self):
        self.capturer = PremierPlusCapturer(Settings.WINDOW_TITLE)
        self.notifier = PremierPlusNotifier(Settings.TELEGRAM_TOKEN, Settings.TELEGRAM_CHAT_ID)
        self.validador = ValidadorImagenes()
        self.last_table_hash = None
        self.running = True  # Bandera para controlar la ejecución

    async def start_monitoring(self):
        logger.info(f"Iniciando monitor para cambios relevantes.... Chat ID: {Settings.TELEGRAM_CHAT_ID}")
        
        if not await self.notifier.verify_connection():
            logger.error("No se pudo conectar a Telegram!")
            return False
        
        debug_folder = "debug_images"
        if not os.path.exists(debug_folder):
            os.makedirs(debug_folder)

        while self.running:  # Ahora verifica la bandera running
            try:
                result = self.capturer.capture_table()
                if result is None:
                    logger.debug("No se detectó la tabla, reintentando...")
                    await asyncio.sleep(2)
                    continue
                    
                full_img, table_img = result
                cv2.imwrite("ultima_captura.png", table_img)
                
                if not self.validador.validar_imagen(table_img):
                    logger.info("Imagen no válida (vacia/procesando) - ignorando")
                    await asyncio.sleep(Settings.SCAN_INTERVAL)
                    continue
                
                logger.debug("Imagen validada correctamente")
                timestamp = int(time.time())
                debug_filename = os.path.join(debug_folder, f'debug_{timestamp}.png')
                cv2.imwrite(debug_filename, table_img)
                logger.debug(f"Imagen guardada: {debug_filename}")
                
                current_hash = hash(table_img.tobytes())
                
                if self.last_table_hash is None or current_hash != self.last_table_hash:
                    logger.info("Enviando imagen a Telegram...")
                    success = await self.notifier.send_image(table_img, f"Actualización {timestamp}")
                    
                    if success:
                        logger.info("Imagen enviada con éxito")
                        self.last_table_hash = current_hash
                    else:
                        logger.error("Fallo al enviar imagen")
                
                await asyncio.sleep(Settings.SCAN_INTERVAL)
                
            except Exception as e:
                logger.error(f"Error en monitor: {str(e)}", exc_info=True)
                await asyncio.sleep(5)
        return True

    def stop_monitoring(self):
        """Detiene el monitoreo"""
        self.running = False
        logger.info("Monitor detenido")

class ImageCleaner:
    @staticmethod
    def clean_images():
        """Limpia la carpeta de imágenes debug"""
        debug_folder = "debug_images"
        try:
            if os.path.exists(debug_folder):
                for filename in os.listdir(debug_folder):
                    file_path = os.path.join(debug_folder, filename)
                    try:
                        if os.path.isfile(file_path) or os.path.islink(file_path):
                            os.unlink(file_path)
                        elif os.path.isdir(file_path):
                            shutil.rmtree(file_path)
                    except Exception as e:
                        logger.error(f"Error al eliminar {file_path}: {e}")
                logger.info("Imágenes limpiadas correctamente")
                return True, f"Se limpiaron las imágenes en {debug_folder}"
            return False, "No se encontró la carpeta debug_images"
        except Exception as e:
            logger.error(f"Error limpiando imágenes: {str(e)}")
            return False, f"Error: {str(e)}"

class App:
    def __init__(self, root):
        self.root = root
        self.root.title("BotPremierPLUS by Moises Flores")
        self.root.geometry("350x200")
        self.monitor = None
        self.thread = None
        
        # Frame principal
        main_frame = ttk.Frame(root)
        main_frame.pack(pady=10, padx=10, fill=tk.BOTH, expand=True)
        
        # Botones
        btn_frame = ttk.Frame(main_frame)
        btn_frame.pack(fill=tk.X, pady=5)
        
        self.btn_start = ttk.Button(
            btn_frame, 
            text="Iniciar Captura", 
            command=self.start_capture,
            width=15
        )
        self.btn_start.pack(side=tk.LEFT, padx=5)
        
        self.btn_stop = ttk.Button(
            btn_frame,
            text="Detener Captura",
            command=self.stop_capture,
            state=tk.DISABLED,
            width=15
        )
        self.btn_stop.pack(side=tk.LEFT, padx=5)
        
        # Botón de limpieza
        self.btn_clean = ttk.Button(
            main_frame,
            text="Limpiar Imágenes",
            command=self.clean_images,
            width=15
        )
        self.btn_clean.pack(pady=10)
        
        # Estado
        self.status = ttk.Label(main_frame, text="Listo para iniciar")
        self.status.pack(pady=5)
    
    def start_capture(self):
        """Inicia el monitoreo"""
        self.btn_start.config(state=tk.DISABLED)
        self.btn_stop.config(state=tk.NORMAL)
        self.status.config(text="Capturando...")
        
        self.monitor = PremierPlusMonitor()
        
        def run_async():
            loop = asyncio.new_event_loop()
            asyncio.set_event_loop(loop)
            result = loop.run_until_complete(self.monitor.start_monitoring())
            self.root.after(0, self.on_monitor_stop, result)
        
        self.thread = threading.Thread(target=run_async, daemon=True)
        self.thread.start()
    
    def stop_capture(self):
        """Detiene el monitoreo"""
        if self.monitor:
            self.monitor.stop_monitoring()
            self.status.config(text="Deteniendo...")
            self.btn_stop.config(state=tk.DISABLED)
    
    def on_monitor_stop(self, result):
        """Callback cuando se detiene el monitor"""
        self.btn_start.config(state=tk.NORMAL)
        self.status.config(text="Listo para iniciar" if result else "Error al detener")
    
    def clean_images(self):
        """Limpia las imágenes debug"""
        success, message = ImageCleaner.clean_images()
        if success:
            messagebox.showinfo("Limpieza Exitosa", message)
        else:
            messagebox.showerror("Error", message)
        self.status.config(text=message)

if __name__ == "__main__":
    # Crear carpeta utilities si no existe
    if not os.path.exists("utilities"):
        os.makedirs("utilities")
    
    # Mover el script de limpieza si no existe
    if not os.path.exists("utilities/limpiar_imagenes.py"):
        try:
            if os.path.exists("limpiar_imagenes.py"):
                shutil.move("limpiar_imagenes.py", "utilities/limpiar_imagenes.py")
        except Exception as e:
            logger.error(f"No se pudo mover el script: {str(e)}")
    
    root = tk.Tk()
    app = App(root)
    root.protocol("WM_DELETE_WINDOW", lambda: (app.stop_capture(), root.destroy()))
    root.mainloop()