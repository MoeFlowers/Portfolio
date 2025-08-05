import pandas as pd
from sklearn.model_selection import train_test_split
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.metrics import MeanAbsoluteError

# Cargar los datos procesados
books = pd.read_csv('peliculas_procesados.csv')

# Preparar los datos de entrada (X) y salida (y)
X = books[['genre_encoded']].values  # Usar directamente genre_encoded
y = books['calificacion'].values  # Usar las calificaciones como salidas

# Dividir los datos en conjuntos de entrenamiento y prueba
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)  # 80% entrenamiento, 20% prueba

# Definir el modelo
model = Sequential([
    Dense(128, activation='relu', input_shape=(X_train.shape[1],)),  # Capa de entrada con 128 neuronas y ReLU
    Dense(64, activation='relu'),  # Capa oculta con 64 neuronas y ReLU
    Dense(1)  # Capa de salida con una neurona para la calificación, sin activación
])

# Compilar el modelo
model.compile(optimizer=Adam(learning_rate=0.001), loss='mean_squared_error', metrics=[MeanAbsoluteError()])  # Usar Adam y MSE

# Entrenar el modelo
history = model.fit(X_train, y_train, epochs=100, batch_size=16, validation_data=(X_test, y_test))  # Entrenamiento por 50 épocas con un tamaño de batch de 16

# Guardar el modelo entrenado
model.save('modelo_entrenado.h5')  # Guardar el modelo en un archivo .h5

print("Modelo entrenado y guardado como 'modelo_entrenado.h5'")

# Extraer la historia del entrenamiento
history_df = pd.DataFrame(history.history)


# Añadir una columna de epochs
history_df['epoch'] = history_df.index + 1

# Guardar la historia del entrenamiento en un archivo CSV
history_df.to_csv('historia_entrenamiento.csv', index=False)

print("Historia del entrenamiento guardada como 'historia_entrenamiento.csv'")
