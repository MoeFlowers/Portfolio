<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="output.css" rel="stylesheet" />
    <title>Login</title>
  </head>
  <body class="font-roboto">
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <div class="flex bg-white shadow-lg rounded-lg w-[800px]">
        <!-- Imagen o Ilustración -->
        <div
          class="w-1/2 rounded-l-lg bg-gradient-to-b from-blue-100 to-green-200 flex items-center justify-center"
        >
          <img
            src="assets/img/comunity-bg.jpg"
            alt="Landscape"
            class="rounded-l-lg max-w-full h-auto"
          />
        </div>

        <!-- Formulario de Login -->
        <div class="w-1/2 p-8 flex flex-col justify-center">
          <h1 class="text-2xl font-bold mb-6 text-center">Santa Eduvigis</h1>
          <form action="../src/controladores/login.php" method="post" class="flex flex-col justify-center w-full">
    <!-- Input Usuario -->
    <div class="mb-4">
        <input
            type="text"
            name="usuario"  
            placeholder="Usuario"
            class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
            autocomplete="off"
            id="username"
            required
        />
    </div>

            <!-- Input Contraseña -->
    <div class="mb-4 relative">
        <input
            type="password"
            name="password" 
            placeholder="Contraseña"
            class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
            id="password"
            required
        />
        <button
            type="button"
            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500"
            onclick="togglePassword()"
        >
            <img
                src="assets/icons/eye-solid.svg"
                alt="Mostrar contraseña"
                class="w-6 h-6"
            />
        </button>
    </div>

    <!-- Enlace Olvidó su Contraseña -->
    <a
        href="pages/change_password.php"
        class="text-sm text-gray-500 hover:text-blue-500 mb-4 text-center block"
    >
        ¿Olvidó su contraseña?
    </a>

    <!-- Botón de Enviar -->
    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition">
        Iniciar Sesión
    </button>
</form>
      </div>
    </div>
  </body>
  <script src="js/login.js"></script>
</html>
