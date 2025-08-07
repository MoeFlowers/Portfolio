<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Sistema de inicio de sesión para Santa Eduvigis." />
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link href="output.css" rel="stylesheet" />
  <title>Login</title>
  <!-- SweetAlert2 -->
  <script src="../src/js/sweetalert2.all.min.js"></script>
  
</head>

<body class="font-roboto">
  <!-- Contenedor Principal -->
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="p-6 md:flex md:w-[800px] bg-white shadow-lg rounded-lg">
      <!-- Imagen o Ilustración -->
      <div
        class="hidden md:w-1/2 md:flex md:items-center md:justify-center md:rounded-l-lg md:bg-gradient-to-b md:from-blue-100 md:to-green-200">
        <img src="assets/img/comunity-bg.jpg" alt="Paisaje" class="rounded-l-lg md:w-full md:h-auto" />
      </div>

      <!-- Formulario de Login -->
      <div class="flex flex-col md:w-1/2 md:p-8 items-center justify-center">
        <h1 class="text-xl md:text-2xl md:font-bold mb-6 text-center">Santa Eduvigis</h1>
        <form id="loginForm" class="flex flex-col justify-center w-full">
          <!-- Input Usuario -->
          <div class="mb-4">
            <input type="text" name="usuario" id="username" placeholder="Usuario"
              class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              autocomplete="off" required />
          </div>

          <!-- Input Contraseña -->
          <div class="mb-4 relative">
            <input type="password" name="password" id="password" placeholder="Contraseña"
              class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              required />
            <button type="button" class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500"
              onclick="togglePassword()">
              <img src="assets/icons/eye-solid.svg" alt="Mostrar contraseña" class="w-6 h-6" />
            </button>
          </div>

          <!-- Enlace Olvidó su Contraseña -->
          <a href="pages/change_password.php" class="text-sm text-gray-500 hover:text-blue-500 mb-4 text-center block">
            ¿Olvidó su contraseña?
          </a>

          <!-- Botón de Enviar -->
          <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition">
            Iniciar Sesión
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="./js/validate.js"></script>
</body>

</html>