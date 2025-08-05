import pandas as pd

# Crear un DataFrame con los datos de libros
data = {
    'id_libro': list(range(1, 36)),
    'titulo': [
'25th Hour', 'Un Mar de Enredos', 'Horizonte Final', 'Enders Game', 'August Underground s Penance',
        'La autopsia de Jane Doe', 'Back By Midnight', 'Invasión del mundo: batalla Los Ángeles', 'Beijing Bastards', 'Más fuertes que su amor',
        'Aliens: Bug Hunt', 'Bungee Jumping', 'Of the Devil', 'Crucifijo', 'Compañera Perfecta',
        'Sonic 3: La Película', 'Mufasa: El Rey León', 'El Brutalista', 'Flow', 'La sociedad de la nieve',
        'Venom: El último baile', 'Rápidos y furiosos 10', 'Top Gun: Maverick', 'Terrifier 3: Payaso Siniestro', 'John Wick 4',
        'Jurassic World: Dominio', 'Transformers: el despertar de las bestias', 'Con Todos Menos Contigo', 'Un vecino gruñón', 'De vuelta a la acción',
        'Culpa mía', 'A través de tu mirada', 'After Para Siempre', 'El Triangulo de la Tristeza', 'Free Guy: tomando el control'
    ],
    'autor': [
        'David Benioff', 'Rob Greenberg', 'Paul W. S. Anderson', 'Orson Scott Card', ' Fred Vogel',
        'André Ovredal', 'Harry Basil', 'Jonathan Liebesman', 'Zhang Yuan', 'Sam Wood',
        'Jonathan Maberry', 'Go Eun Nim', 'Kelton Jones', 'Glenn Kendrick Ackermann', 'Zach Cregger',
        'Jeff Fowler', 'Barry Jenkins', 'Brady Corbet', 'Gints Zilbalodis', 'Juan Antonio Bayona',
        'Kelly Marcel', ' Louis Leterrier', 'Joseph Kosinski', 'Damien Leone', 'Chad Stahelski',
        'Colin Trevorrow', 'Steven Caple Jr.', 'Will Gluck', 'Andrzej Sapkowski', 'Seth Gordon',
        'Domingo González', 'Marçal Forès', 'Castille Landon', 'Ruben Östlund', 'Shawn Levy'
    ],
    'genero': [
        'Drama', 'Comedia', 'Ciencia Ficción', 'Ciencia Ficción', 'Terror',
        'Terror', 'Comedia', 'Ciencia Ficción', 'Drama', 'Drama',
        'Terror', 'Drama', 'Terror', 'Terror', 'Drama',
        'Aventura', 'Aventura', 'Drama', 'Aventura', 'Aventura',
        'Ciencia Ficción', 'Acción', 'Acción', 'Terror', 'Acción',
        'Ciencia Ficción', 'Acción', 'Comedia', 'Aventura', 'Crecimiento Personal',
        'Drama', 'Comedia', 'Comedia', 'Comedia', 'Comedia'
    ],
    'calificacion': [
        4.8, 4.7, 4.9, 4.6, 4.4, 4.8, 4.5, 4.7, 4.8, 4.9,
        4.6, 4.9, 4.8, 4.7, 4.7, 4.5, 4.4, 4.8, 4.5, 4.6,
        4.7, 4.4, 4.5, 4.6, 4.8, 4.7, 4.6, 4.9, 4.8, 4.5,
        4.6, 4.7, 4.8, 4.9, 4.5
    ],
    'image_url': [
        'images/downloaded_image_1.jpg', 'images/downloaded_image_2.jpg', 'images/downloaded_image_3.jpg', 'images/downloaded_image_4.jpg', 'images/downloaded_image_5.jpg',
        'images/downloaded_image_6.jpg', 'images/downloaded_image_7.jpg', 'images/downloaded_image_8.jpg', 'images/downloaded_image_9.jpg', 'images/downloaded_image_10.jpg',
        'images/downloaded_image_11.jpg', 'images/downloaded_image_12.jpg', 'images/downloaded_image_13.jpg', 'images/downloaded_image_14.jpg', 'images/downloaded_image_15.jpg',
        'images/downloaded_image_16.jpg', 'images/downloaded_image_17.jpg', 'images/downloaded_image_18.jpg', 'images/downloaded_image_19.jpg', 'images/downloaded_image_20.jpg',
        'images/downloaded_image_21.jpg', 'images/downloaded_image_22.jpg', 'images/downloaded_image_23.jpg', 'images/downloaded_image_24.jpg', 'images/downloaded_image_25.jpg',
        'images/downloaded_image_26.jpg', 'images/downloaded_image_27.jpg', 'images/downloaded_image_28.jpg', 'images/downloaded_image_29.jpg', 'images/downloaded_image_30.jpg',
        'images/downloaded_image_31.jpg', 'images/downloaded_image_32.jpg', 'images/downloaded_image_33.jpg', 'images/downloaded_image_34.jpg', 'images/downloaded_image_35.jpg'
    ]
}

# Crear el DataFrame
df = pd.DataFrame(data)

# Guardar el DataFrame en un archivo CSV
df.to_csv('peliculas.csv', index=False)

print("Archivo peliculas.csv creado con éxito.")
