from flask import Flask, request, render_template
import pandas as pd
import numpy as np
from tensorflow.keras.models import load_model

app = Flask(__name__)

# Cargar el modelo entrenado y los datos de libros
model = load_model('modelo_entrenado.h5')
books = pd.read_csv('peliculas_procesados.csv')

@app.route('/')
def index():
    return render_template('index.html', books=books)

@app.route('/recommend')
def recommend():
    id_libro = int(request.args.get('id_libro'))
    book = books[books['id_libro'] == id_libro].iloc[0]
    book_genre_encoded = book['genre_encoded']
    
    # Realizar la predicción
    predicted_rating = model.predict(np.array([[book_genre_encoded]]))[0][0]
    
    # Encontrar libros con la misma categoría
    same_genre_books = books[books['genre_encoded'] == book_genre_encoded]
    
    # Ordenar los libros por calificación y seleccionar los top N (sin incluir el libro actual)
    recommended_books = same_genre_books[same_genre_books['id_libro'] != id_libro].sort_values(by='calificacion', ascending=False)
    
    return render_template(
        'recommendations.html', 
        book=book, 
        recommended_books=recommended_books, 
        predicted_rating=predicted_rating
    )

if __name__ == "__main__":
    app.run(host='127.0.0.1', port=8080)