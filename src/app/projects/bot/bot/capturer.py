import cv2
import numpy as np
import pyautogui
import logging
from typing import Optional, Tuple
from config.settings import Settings

class PremierPlusCapturer:
    def __init__(self, window_title: str):
        self.window_title = window_title
        self.logger = logging.getLogger(__name__)
        self.table_region = Settings.TABLE_REGION

    def capture_table(self) -> Optional[Tuple[np.ndarray, np.ndarray]]:
        """Captura la ventana y extrae específicamente la región de la tabla."""
        try:
            windows = pyautogui.getWindowsWithTitle(self.window_title)
            if not windows:
                self.logger.warning(f"Ventana '{self.window_title}' no encontrada")
                return None

            window = windows[0]
            
            # Activar ventana para asegurar captura
            if not window.isActive:
                window.activate()
                pyautogui.sleep(0.3)

            # Capturar toda la ventana
            full_screenshot = pyautogui.screenshot(region=(
                window.left,
                window.top,
                window.width,
                window.height
            ))
            full_img = cv2.cvtColor(np.array(full_screenshot), cv2.COLOR_RGB2BGR)

            # Extraer región de la tabla
            table_img = full_img[
                self.table_region["top"]:self.table_region["top"]+self.table_region["height"],
                self.table_region["left"]:self.table_region["left"]+self.table_region["width"]
            ]

            return full_img, table_img

        except Exception as e:
            self.logger.error(f"Error capturando tabla: {str(e)}", exc_info=True)
            return None