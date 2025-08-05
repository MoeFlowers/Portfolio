<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Registro de Usuario</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="min-h-screen flex items-center">
    <!-- Fondo con imagen odontológica -->
    <div class="fixed inset-0 -z-10">
        <img src="../../assets/images/bg.jpg" alt="Fondo odontológico" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-cyan-50 mix-blend-overlay"></div>
    </div>
    <!-- Contenedor principal -->
    <div class="max-w-md mx-auto p-8 bg-gray-100 rounded-xl shadow-2xl w-full backdrop-blur-sm">
        <div class="text-center mb-8">
            <img src="../../assets/images/logo-removebg-preview.png" alt="IPSFANB Logo" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-blue-800">Registro de Usuario</h1>
            <p class="text-gray-600">Complete el formulario para crear una nueva cuenta</p>
        </div>

        <!-- Formulario de registro -->
        <form id="registerForm" class="space-y-6">
            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                    <label for="username" class="block text-sm font-medium text-gray-700">Usuario</label>
                </div>
                <input id="username" name="username" type="text" required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    placeholder="Cree un nombre de usuario">
            </div>

            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                        <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                    </svg>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                </div>
                <input id="email" name="email" type="email" required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    placeholder="Ingrese su correo electrónico">
            </div>

            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                </div>
                <input id="password" name="password" type="password" required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    placeholder="Cree una contraseña segura">
            </div>

            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                </div>
                <input id="confirmPassword" name="confirmPassword" type="password" required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    placeholder="Repita su contraseña">
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                    Registrarse
                </button>
            </div>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">¿Ya tienes una cuenta?</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="../login.php"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 pl-1 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>

    <!-- Script para validación de registro -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden. Por favor, inténtelo de nuevo.'
                });
                return;
            }

            // Aquí iría la lógica para enviar los datos al servidor
            Swal.fire({
                icon: 'success',
                title: 'Registro exitoso',
                text: 'Tu cuenta ha sido creada correctamente'
            }).then(() => {
                window.location.href = '../../views/login.php';
            });
        });
    </script>
</body>

</html>