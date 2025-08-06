import cv2
import pytesseract
import numpy as np
import logging
from typing import Optional, Dict, List
import re

class TableProcessor:
    def __init__(self):
        self.logger = logging.getLogger(__name__)
        pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'
        self.table_headers = ["Sorteo", "Jugada", "Monto", "Estatus"]

    def preprocess_image(self, image: np.ndarray) -> np.ndarray:
        """Preprocesamiento optimizado para texto rojo y negro"""
        try:
            # Separar canales de color
            b, g, r = cv2.split(image)

            # Procesamiento para texto negro (usamos el canal azul que tiene mejor contraste)
            _, black_text = cv2.threshold(b, 150, 255, cv2.THRESH_BINARY_INV)

            # Procesamiento para texto rojo
            red_mask = cv2.bitwise_and(
                cv2.threshold(r, 200, 255, cv2.THRESH_BINARY)[1],
                cv2.bitwise_not(cv2.threshold(g, 100, 255, cv2.THRESH_BINARY)[1])
            )

            # Combinar ambos textos
            combined = cv2.bitwise_or(black_text, red_mask)

            # Operaciones morfológicas para limpieza
            kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (2,2))
            cleaned = cv2.morphologyEx(combined, cv2.MORPH_CLOSE, kernel)

            cv2.imwrite('debug_preprocessed.png', cleaned)
            return cleaned

        except Exception as e:
            self.logger.error(f"Error en preprocesamiento: {str(e)}")
            return image

    def extract_table_data(self, table_image: np.ndarray) -> Optional[List[Dict]]:
        """Extracción optimizada para texto rojo/negro"""
        try:
            processed_img = self.preprocess_image(table_image)

            custom_config = r'''
                --oem 3 --psm 6 
                -c tessedit_char_whitelist=0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÑ-. 
                -c textord_projection_scale=0.3
                -c language_model_penalty_non_dict_word=0.5
            '''

            text = pytesseract.image_to_string(
                processed_img, 
                lang='spa',
                config=custom_config
            )

            self.logger.debug(f"Texto crudo mejorado:\n{text}")
            return self.parse_table_text(text)
        except Exception as e:
            self.logger.error(f"Error en extracción de datos: {str(e)}")
            return None
    
    def parse_table_text(self, text: str) -> List[Dict]:
        """Parseo optimizado para texto rojo/negro"""
        lines = [line.strip() for line in text.split('\n') if line.strip()]
        table_data = []

        # Expresión regular mejorada
        pattern = re.compile(
            r'([A-ZÁÉÍÓÚÑ]{2,})(?:\s|\.)*(\d+\s*-\s*[A-ZÁÉÍÓÚÑ]+)(?:\s|\.)*(\d+)\s+(AGOTADO|REDUCIDO)',
            re.IGNORECASE
        )

        for line in lines:
            # Normalizar espacios y caracteres especiales
            line = re.sub(r'[^\w\s-]', '', line)
            match = pattern.search(line)
            if match and match.group(4) in ['AGOTADO', 'REDUCIDO']:	
                table_data.append({
                    "Sorteo": match.group(1),
                    "Jugada": match.group(2).replace('.', ''),
                    "Monto": match.group(3),
                    "Estatus": match.group(4)
                })

        return table_data