from telegram import Bot
from telegram import constants
import logging
import numpy as np
import cv2
from io import BytesIO
import asyncio
from telegram.error import TelegramError, NetworkError

class PremierPlusNotifier:
    def __init__(self, token: str, chat_id: str):
        self.bot = Bot(token=token)
        self.chat_id = chat_id
        self.logger = logging.getLogger(__name__)
        self.last_message_id = None

    async def verify_connection(self) -> bool:
        try:
            me = await asyncio.wait_for(self.bot.get_me(), timeout=10.0)  # Timeout de 10 segundos
            self.logger.info(f"Conectado como @{me.username}")
            return True
        except asyncio.TimeoutError:
            self.logger.error("Timeout al conectar con Telegram")
            return False
        except Exception as e:
            self.logger.error(f"Error de conexión: {str(e)}")
            return False

    def _prepare_image(self, image: np.ndarray) -> BytesIO:
        """Prepara la imagen para enviar a Telegram"""
        try:
            success, img_encoded = cv2.imencode('.jpg', image, [
                int(cv2.IMWRITE_JPEG_QUALITY), 85,
                int(cv2.IMWRITE_JPEG_PROGRESSIVE), 1
            ])
            
            if not success:
                self.logger.error("Error al codificar imagen")
                return None
                
            img_bytes = BytesIO(img_encoded.tobytes())
            img_bytes.seek(0)
            return img_bytes
            
        except Exception as e:
            self.logger.error(f"Error preparando imagen: {str(e)}")
            return None

    async def _send_with_retry(self, send_func, **kwargs) -> bool:
        """Método interno para reintentos"""
        for attempt in range(3):
            try:
                result = await send_func(
                    chat_id=self.chat_id,
                    parse_mode=constants.ParseMode.HTML,
                    **kwargs
                )
                self.last_message_id = result.message_id
                return True
            except NetworkError:
                wait_time = (attempt + 1) * 2
                self.logger.warning(f"Error de red. Reintento {attempt+1}/3 en {wait_time}s...")
                await asyncio.sleep(wait_time)
            except TelegramError as e:
                self.logger.error(f"Error de Telegram: {str(e)}")
                break
        
        return False

    async def send_image(self, image: np.ndarray, caption: str = "") -> bool:
        """Envía solo la imagen a Telegram con manejo robusto de errores"""
        try:
            # 1. Verificar conexión
            self.logger.info("Verificando conexión con Telegram...")
            if not await self.verify_connection():
                return False

            # 2. Preparar imagen
            img_bytes = self._prepare_image(image)
            if not img_bytes:
                self.logger.error("No se pudo preparar la imagen para enviar")
                return False

            # 3. Enviar imagen con caption
            return await self._send_with_retry(
                self.bot.send_photo,
                photo=img_bytes,
                caption=caption
            )

        except Exception as e:
            self.logger.critical(f"Error fatal al enviar imagen: {str(e)}", exc_info=True)
            return False

    async def send_alert(self, message: str) -> bool:
        """Envía mensaje de alerta"""
        return await self._send_with_retry(
            self.bot.send_message,
            text=f"⚠️ <b>{message}</b>"
        )