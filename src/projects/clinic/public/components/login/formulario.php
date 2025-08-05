<?php
session_start();
// Mostrar mensajes de error/success si existen
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
// Limpiar los mensajes después de mostrarlos
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<!-- Agrega esto arriba de tu formulario -->
<?php if ($error): ?>
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
        <p><?php echo htmlspecialchars($error); ?></p>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        <p><?php echo htmlspecialchars($success); ?></p>
    </div>
<?php endif; ?>

<form action="../components/login/validar_login.php" method="POST" class="space-y-6">
    <!-- El resto de tu formulario permanece igual -->
    <div>
        <div class="flex items-center mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
            </svg>
            <label for="usuario" class="block text-sm font-medium text-gray-700">Usuario</label>
        </div>
        <input type="text" name="usuario" id="usuario"
            class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
            required>
    </div>

    <div>
        <div class="flex items-center mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
            </svg>
            <label for="contraseña" class="block text-sm font-medium text-gray-700">Contraseña</label>
        </div>
        <input type="password" name="contraseña" id="contraseña"
            class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
            required>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input type="checkbox" id="recordarme" name="recordarme"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="recordarme" class="ml-2 block text-sm text-gray-700">Recordarme</label>
        </div>
        <div class="text-sm">
            <a href="../views/recuperar/recuperar.php" class="font-medium text-blue-600 hover:text-blue-500">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <div>
        <button type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
            Iniciar Sesión
        </button>
    </div>
</form>