# build_installer.py
import PyInstaller.__main__
import shutil
import os
from cryptography.fernet import Fernet

# 1. Configuración de compilación
APP_NAME = "BotPremierPLUS by Moises Flores"
VERSION = "1.0"
PASSWORD = "0412559455629868346m"  # Cambia esto

# 2. Generar clave de cifrado (para protección)
key = Fernet.generate_key()
cipher_suite = Fernet(key)

# 3. Cifrar archivos sensibles
def encrypt_file(file_path):
    with open(file_path, 'rb') as f:
        encrypted = cipher_suite.encrypt(f.read())
    with open(file_path, 'wb') as f:
        f.write(encrypted)

# 4. Compilar con PyInstaller
PyInstaller.__main__.run([
    'main.py',
    '--onefile',
    '--windowed',
    '--name=%s' % APP_NAME,
    '--icon=assets/icon.ico',  # Crea esta carpeta/archivo
    '--add-data=bot/referencias;bot/referencias',
    '--add-data=config/settings.py;config',
    '--add-data=utilities;utilities',
    '--hidden-import=tkinter',
    '--noconfirm'
])

# 5. Crear estructura para instalador
os.makedirs('dist/installer', exist_ok=True)
shutil.copytree('dist', 'dist/installer/files')
shutil.make_archive('dist/installer/files', 'zip', 'dist/installer/files')

# 6. Generar script de instalación (installer.py)
with open('dist/installer/installer.py', 'w') as f:
    f.write(f"""
import tkinter as tk
from tkinter import messagebox
import os
import zipfile
from cryptography.fernet import Fernet

def install():
    key = {key}
    cipher = Fernet(key)
    
    # Verificar contraseña
    passwd = input("Ingrese la contraseña de instalación: ")
    if passwd != "{PASSWORD}":
        messagebox.showerror("Error", "Contraseña incorrecta")
        return
    
    # Descomprimir
    with zipfile.ZipFile('files.zip', 'r') as zip_ref:
        zip_ref.extractall('C:\\\\Program Files\\\\{APP_NAME}')
    
    # Descifrar archivos
    for root, _, files in os.walk('C:\\\\Program Files\\\\{APP_NAME}'):
        for file in files:
            if file.endswith('.enc'):
                with open(os.path.join(root, file), 'rb') as f:
                    decrypted = cipher.decrypt(f.read())
                with open(os.path.join(root, file.replace('.enc', '')), 'wb') as f:
                    f.write(decrypted)
    
    messagebox.showinfo("Éxito", "Instalación completada")

if __name__ == "__main__":
    install()
""")

print("Compilación completada. Ejecuta 'iscc installer.iss' para crear el instalador final")