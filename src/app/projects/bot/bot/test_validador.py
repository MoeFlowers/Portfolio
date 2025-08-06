from confirmacion import ValidadorImagenes
import cv2
import os
import numpy as np

# Configura rutas absolutas
base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
tests_dir = os.path.join(base_dir, "bot", "tests")

validador = ValidadorImagenes()
test_images = ["vacia.png", "procesando.png", "procesando2.png", "vacia2.png"]

for img_name in test_images:
    img_path = os.path.join(tests_dir, img_name)
    img = cv2.imread(img_path)
    if img is None:
        print(f"Error: No se pudo leer {img_path}")
        continue
    
    # Preprocesamiento idéntico al validador
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    _, binary = cv2.threshold(gray, 200, 255, cv2.THRESH_BINARY)
    
    # Debug: Mostrar diferencias solo para vacia.png
    if img_name == "vacia.png":
        if hasattr(validador, 'ref_vacia'):
            diff = cv2.absdiff(binary, validador.ref_vacia)
            print(f"Diferencias con vacia.png: {np.sum(diff)} píxeles")
            cv2.imwrite('debug_diff.png', diff)  # Guarda imagen de diferencias
    
    resultado = validador.validar_imagen(img)
    print(f"{img_name}: {'ENVIAR' if resultado else 'NO enviar'}")