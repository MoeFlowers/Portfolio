<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="favicon.ico" type="image/x-icon" />
  <link href="output.css" rel="stylesheet" />
  <title>Login</title>
</head>

<body class="font-roboto bg-gray-100">
  <!-- Contenedor Principal -->
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex flex-col w-1/2 sm:w-full lg:w-full  lg:max-w-4xl lg:flex-row bg-white shadow-lg rounded-lg  overflow-hidden">
      <div class="w-72 h-50 lg:w-1/2 flex bg-gradient-to-b from-blue-100 to-green-200 items-center justify-center">
        <img
          src="assets/img/comunity-bg.jpg"
          alt="Paisaje"
          class="w-full h-auto object-cover" />
      </div>
      <!-- Formulario -->
      <!-- <div class="lg:flex lg:w-1/2 sm:w-1/12 md:w-1/6 sm:p-8 md:p-12 flex flex-col justify-center">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Santa Eduvigis</h1>
        <form action="../src/controllers/login.php" method="post" class="space-y-4"> -->
      <!-- Input Usuario -->
      <!-- <div>
            <label for="usuario" class="sr-only">Usuario</label>
            <input
              type="text"
              id="usuario"
              name="usuario"
              placeholder="Usuario"
              class="w-full px-4 py-3 border rounded-lg bg-gray-50 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              required />
          </div> -->

      <!-- Input Contraseña -->
      <div class="relative">
        <label for="password" class="sr-only">Contraseña</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Contraseña"
          class="w-full px-4 py-3 border rounded-lg bg-gray-50 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
          required />
        <button
          type="button"
          class="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500"
          onclick="togglePassword()">
          <img src="assets/icons/eye-solid.svg" alt="Mostrar contraseña" class="w-5 h-5" />
        </button>
      </div>

      <!-- Enlace Olvidó su Contraseña -->
      <a
        href="pages/change_password.php"
        class="text-sm text-blue-500 hover:underline text-center block">
        ¿Olvidó su contraseña?
      </a>

      <!-- Botón de Enviar -->
      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition">
        Iniciar Sesión
      </button>
      </form>
    </div>
  </div>
  </div>
  <script src="js/login.js"></script>
</body>

</html>