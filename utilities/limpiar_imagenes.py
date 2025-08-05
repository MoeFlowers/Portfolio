import os
import shutil
from datetime import datetime, time as dt_time
import logging

# Configuración básica de logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def limpiar_carpeta_imagenes():
    """Limpia la carpeta de imágenes debug según el horario configurado"""
    debug_folder = "debug_images"
    cleanup_time = dt_time(19, 5)  # 7:05 PM
    
    if not os.path.exists(debug_folder):
        logger.warning(f"La carpeta {debug_folder} no existe")
        return False
    
    try:
        # Verificar si es después de las 7:05 PM
        if datetime.now().time() >= cleanup_time:
            # Borrar todo el contenido de la carpeta
            for filename in os.listdir(debug_folder):
                file_path = os.path.join(debug_folder, filename)
                try:
                    if os.path.isfile(file_path) or os.path.islink(file_path):
                        os.unlink(file_path)
                    elif os.path.isdir(file_path):
                        shutil.rmtree(file_path)
                except Exception as e:
                    logger.error(f"Error al eliminar {file_path}: {e}")
            
            logger.info(f"Todos los archivos en {debug_folder} fueron eliminados")
            return True
        else:
            logger.info(f"Aún no es hora de limpieza (después de las {cleanup_time})")
            return False
            
    except Exception as e:
        logger.error(f"Error inesperado: {str(e)}")
        return False

if __name__ == "__main__":
    print("=== Ejecutando limpieza de imágenes debug ===")
    limpiar_carpeta_imagenes()
    print("=== Proceso completado ===")