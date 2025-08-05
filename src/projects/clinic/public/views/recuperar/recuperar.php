<?php
session_start();
require_once '../../components/database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $username = trim($_POST['username']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validaciones básicas
    if (empty($username)) {
        $_SESSION['error'] = "El campo usuario es requerido";
    } elseif (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['error'] = "Debe completar ambos campos de contraseña";
    } elseif ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Las contraseñas no coinciden";
    } elseif (strlen($newPassword) < 8) {
        $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";
    } else {
        // Conexión a la base de datos
        $conn = getDBConnection();

        // Verificar si el usuario existe
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['error'] = "El usuario no existe en nuestro sistema";
        } else {
            $user = $result->fetch_assoc();
            $userId = $user['id_usuario'];

            // Generar hash MD5 (compatible con tu sistema actual)
            $hashedPassword = md5($newPassword);

            // Actualizar contraseña
            $updateStmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?");
            $updateStmt->bind_param("si", $hashedPassword, $userId);

            if ($updateStmt->execute()) {
                $_SESSION['success'] = "Contraseña actualizada correctamente";
                header("Location: ../login.php");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar la contraseña";
            }

            $updateStmt->close();
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Recuperar Contraseña</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../public/assets/css/all.min.css">
    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
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
            <h1 class="text-2xl font-bold text-blue-800">Recuperar Contraseña</h1>
            <p class="text-gray-600">Ingrese su usuario y establezca una nueva contraseña</p>
        </div>

        <!-- Formulario de recuperación -->
        <form id="recoverForm" method="POST" class="space-y-6">
            <!-- Campos del formulario (igual que antes) -->
            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                    <label for="username" class="block text-sm font-medium text-gray-700">Usuario</label>
                </div>
                <input id="username" name="username" type="text" required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    placeholder="Ingrese su usuario">
            </div>

            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                    <label for="newPassword" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                </div>
                <div class="relative">
                    <input id="newPassword" name="newPassword" type="password" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                        placeholder="Ingrese nueva contraseña">
                </div>
            </div>

            <div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                </div>
                <div class="relative">
                    <input id="confirmPassword" name="confirmPassword" type="password" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                        placeholder="Confirme su contraseña">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                    Actualizar Contraseña
                </button>
            </div>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-100 text-gray-500">¿Recordaste tu contraseña?</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="../login.php"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 pl-1 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Volver a Iniciar Sesión
                </a>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Mostrar SweetAlert2 según la sesión PHP
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $_SESSION['error']; ?>',
                confirmButtonColor: '#3b82f6'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?php echo $_SESSION['success']; ?>',
                confirmButtonColor: '#3b82f6'
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        // Validación del formulario antes de enviar
        document.getElementById('recoverForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden. Por favor, verifique.',
                    confirmButtonColor: '#3b82f6'
                });
            }
        });
    </script>
</body>

</html>