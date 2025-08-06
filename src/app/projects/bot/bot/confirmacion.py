import cv2
import numpy as np
import os
from typing import Optional

class ValidadorImagenes:
    def __init__(self):
        self.refs = self._cargar_referencias()
        
    def _cargar_referencias(self) -> dict:
        """Carga todas las imágenes de referencia"""
        base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        refs = {}
        
        for estado in ['vacia', 'procesando', 'limite_excedido', 'procesando2']:
            for i in range(1, 4):  # carga vacia1.png, vacia2.png, etc.
                path = os.path.join(base_dir, "bot", "tests", f"{estado}{i if i>1 else ''}.png")
                if os.path.exists(path):
                    ref_img = self._preprocesar_imagen(cv2.imread(path))
                    if ref_img is not None:
                        refs[f"{estado}{i}"] = ref_img
                        print(f"Cargada referencia: {path}")  # Debug
        
        if not refs:
            raise FileNotFoundError("No se encontraron imágenes de referencia")
        return refs

    def _preprocesar_imagen(self, img: np.ndarray) -> Optional[np.ndarray]:
        """Preprocesamiento consistente para todas las imágenes"""
        if img is None:
            return None
            
        # Convertir a escala de grises y aplicar threshold
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        _, thresh = cv2.threshold(gray, 220, 255, cv2.THRESH_BINARY)
        
        # Reducir ruido con operaciones morfológicas
        kernel = np.ones((3,3), np.uint8)
        cleaned = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel)
        
        return cleaned

    def validar_imagen(self, img: np.ndarray) -> bool:
        """Determina si la imagen contiene datos válidos"""
        if img is None:
            return False
            
        # Preprocesar la imagen de entrada igual que las referencias
        img_processed = self._preprocesar_imagen(img)
        if img_processed is None:
            return False

        # Guardar imagen para debug
        cv2.imwrite('ultima_validacion.png', img_processed)
        
        # Comparar con todas las referencias
        for ref_name, ref_img in self.refs.items():
            if self._son_iguales(img_processed, ref_img):
                print(f"Coincide con referencia: {ref_name}")  # Debug
                return False
                
        return True

    def _son_iguales(self, img1: np.ndarray, img2: np.ndarray, umbral: float = 0.98) -> bool:
        """Comparación robusta con tolerancia ajustable"""
        if img1.shape != img2.shape:
            return False
            
        # Calcular similitud estructural
        diff = cv2.absdiff(img1, img2)
        porcentaje_diferente = np.count_nonzero(diff) / diff.size
        return porcentaje_diferente < (1 - umbral)