import pandas as pd

# Cargar el archivo CSV con la historia del entrenamiento
history_df = pd.read_csv('historia_entrenamiento.csv')

# Mostrar las primeras filas del DataFrame
print(history_df.head())

# Graficar las métricas de entrenamiento
import matplotlib.pyplot as plt

plt.figure(figsize=(12, 5))

# Graficar la pérdida
plt.subplot(1, 2, 1)
plt.plot(history_df['loss'], label='Loss')
plt.plot(history_df['val_loss'], label='Val Loss')
plt.xlabel('Epoch')
plt.ylabel('perdida')
plt.title('perdida durante el entrenamiento')
plt.legend()

# Graficar el error absoluto medio
plt.subplot(1, 2, 2)
plt.plot(history_df['mean_absolute_error'], label='Mean Absolute Error')
plt.plot(history_df['val_mean_absolute_error'], label='Val Mean Absolute Error')
plt.xlabel('Epoch')
plt.ylabel('Error absoluto')
plt.title('Error Absoluto Medio durante el entrenamiento')
plt.legend()

plt.tight_layout()
plt.show()
