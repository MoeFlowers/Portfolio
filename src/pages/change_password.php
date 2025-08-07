<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../favicon.ico" type="image/x-icon">
  <link href="../output.css" rel="stylesheet" />
  <title>Cambiar Contraseña</title>
</head>

<body class="font-roboto">
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="p-6 flex md:w-[800px] bg-white shadow-lg rounded-lg">
      <!-- Imagen o Ilustración -->
      <div
        class="hidden md:w-1/2 md:flex md:items-center md:justify-center md:rounded-l-lg md:bg-gradient-to-b md:from-blue-100 md:to-green-200">
        <img src="../assets/img/comunity-bg-2.jpg" alt="Landscape" class="md:rounded-l-lg md:w-full md:h-full object-cover" />
      </div>

      <!-- Formulario de Cambiar Contraseña -->
      <div class="flex flex-col md:w-1/2 md:p-8  md:items-center justify-center">
        <h1 class="text-xl md:text-2xl md:font-bold mb-6 text-center">
          Cambiar Contraseña
        </h1>
        <form action="../src/controllers/changepass.php" method="post" class="flex flex-col justify-center w-full">
          <!-- Input Usuario -->
          <div class="mb-4">
            <input
              type="text"
              placeholder="Usuario"
              class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              autocomplete="off" />
          </div>

          <!-- Input Cédula -->
          <div class="mb-4">
            <input
              type="text"
              placeholder="Cédula"
              class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              autocomplete="off" />
          </div>

          <!-- Input Nueva Contraseña -->
          <div class="mb-4 relative">
            <input
              type="password"
              placeholder="Nueva Contraseña"
              class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              id="newPassword" />
            <button
              type="button"
              class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500"
              onclick="togglePassword('newPassword')">
              <img
                src="../assets/icons/eye-solid.svg"
                alt="Mostrar contraseña"
                class="w-6 h-6" />
            </button>
          </div>

          <!-- Input Confirmar Contraseña -->
          <div class="mb-4 relative">
            <input
              type="password"
              placeholder="Confirmar Contraseña"
              class="w-full p-3 border rounded-lg bg-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white"
              id="confirmPassword" />
            <button
              type="button"
              class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500"
              onclick="togglePassword('confirmPassword')">
              <img
                src="../assets/icons/eye-solid.svg"
                alt="Mostrar contraseña"
                class="w-6 h-6" />
            </button>
          </div>

          <!-- Enlace Olvidó su Contraseña -->
          <a
            href="../index.php"
            class="text-sm text-gray-500 hover:text-blue-500 mb-4 text-center block">
            Iniciar Sesion
          </a>

          <!-- Botón de Cambiar Contraseña -->
          <button
            class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition">
            Cambiar Contraseña
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
<script>
  function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = passwordInput.nextElementSibling; // Obtén el botón asociado
    const isPasswordVisible = passwordInput.type === "text";

    // Cambia el tipo del input
    passwordInput.type = isPasswordVisible ? "password" : "text";

    // Cambia el ícono
    toggleButton.querySelector("img").src = isPasswordVisible ?
      "../assets/icons/eye-solid.svg" // Ícono de ojo cerrado
      :
      "../assets/icons/eye-slash-solid.svg"; // Ícono de ojo abierto
  }
</script>

</html>