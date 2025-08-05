import pyautogui
import time
import cv2
import numpy as np

def get_table_coordinates():
    print("1. Activa la ventana de '::PREMIERPLUSS:: Pago / Cambio' y espera 5 segundos...")
    time.sleep(5)
    
    # Obtener posición de la ventana
    try:
        win = pyautogui.getWindowsWithTitle("::PREMIERPLUSS:: Pago / Cambio")[0]
        print(f"\n2. Ventana encontrada en posición: Left={win.left}, Top={win.top}, Width={win.width}, Height={win.height}")
        
        # Capturar región de la ventana
        screenshot = pyautogui.screenshot(region=(win.left, win.top, win.width, win.height))
        screenshot.save("window_capture.png")
        print("Captura guardada como 'window_capture.png'")
        
        # Mostrar imagen para selección
        img = cv2.cvtColor(np.array(screenshot), cv2.COLOR_RGB2BGR)
        cv2.imshow("Selecciona la tabla (presiona 's' para guardar coordenadas)", img)
        
        # Crear ROI selector
        fromCenter = False
        r = cv2.selectROI("Selecciona la tabla (presiona 's' para guardar coordenadas)", img, fromCenter)
        cv2.destroyAllWindows()
        
        print(f"\n3. Coordenadas de la tabla (relativas a la ventana):")
        print(f"Top: {r[1]}")
        print(f"Left: {r[0]}")
        print(f"Width: {r[2]}")
        print(f"Height: {r[3]}")
        
        # Mostrar preview de lo que se capturará
        table_img = img[r[1]:r[1]+r[3], r[0]:r[0]+r[2]]
        cv2.imshow("Preview de la tabla capturada", table_img)
        cv2.waitKey(0)
        cv2.destroyAllWindows()
        
        return r[0], r[1], r[2], r[3]
        
    except Exception as e:
        print(f"Error: {e}")
        return None

if __name__ == "__main__":
    coords = get_table_coordinates()
    if coords:
        print("\nConfiguración para settings.py:")
        print(f"TABLE_REGION = {{\n"
              f"    'top': {coords[1]},\n"
              f"    'left': {coords[0]},\n"
              f"    'width': {coords[2]},\n"
              f"    'height': {coords[3]}\n}}")