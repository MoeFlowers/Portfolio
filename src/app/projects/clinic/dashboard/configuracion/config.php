<?php
session_start();
require_once '../../components/database/database.php';

// Verificar autenticación
if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: ../../components/login/login.php');
    exit();
}

// Obtener conexión a la base de datos
$conexion = getDBConnection();

// Obtener datos del usuario según su tipo
$usuario = [];
$id = $_SESSION['tipo_usuario'] === 'Empleado' ? $_SESSION['empleado']['id_empleado'] : $_SESSION['paciente']['id_paciente'];

if ($_SESSION['tipo_usuario'] === 'Empleado') {
    $query = "SELECT * FROM empleados WHERE id_empleado = ?";
} else {
    $query = "SELECT * FROM pacientes WHERE id_paciente = ?";
}

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Procesar actualización de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actualizar_datos'])) {
        // Actualizar información básica
        $primer_nombre = $_POST['primer_nombre'];
        $segundo_nombre = $_POST['segundo_nombre'] ?? '';
        $primer_apellido = $_POST['primer_apellido'];
        $segundo_apellido = $_POST['segundo_apellido'] ?? '';
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'] ?? '';

        if ($_SESSION['tipo_usuario'] === 'Empleado') {
            $update_query = "UPDATE empleados SET 
                            primer_nombre = ?, 
                            segundo_nombre = ?, 
                            primer_apellido = ?, 
                            segundo_apellido = ?, 
                            telefono = ?, 
                            correo = ? 
                            WHERE id_empleado = ?";
        } else {
            $update_query = "UPDATE pacientes SET 
                            primer_nombre = ?, 
                            segundo_nombre = ?, 
                            primer_apellido = ?, 
                            segundo_apellido = ?, 
                            telefono = ?, 
                            correo = ? 
                            WHERE id_paciente = ?";
        }

        $stmt = $conexion->prepare($update_query);
        $stmt->bind_param(
            "ssssssi",
            $primer_nombre,
            $segundo_nombre,
            $primer_apellido,
            $segundo_apellido,
            $telefono,
            $correo,
            $id
        );

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "success|Datos actualizados correctamente";
            // Actualizar datos en sesión
            if ($_SESSION['tipo_usuario'] === 'Empleado') {
                $_SESSION['empleado']['primer_nombre'] = $primer_nombre;
                $_SESSION['empleado']['segundo_nombre'] = $segundo_nombre;
                $_SESSION['empleado']['primer_apellido'] = $primer_apellido;
                $_SESSION['empleado']['segundo_apellido'] = $segundo_apellido;
                $_SESSION['empleado']['telefono'] = $telefono;
                $_SESSION['empleado']['correo'] = $correo;
            } else {
                $_SESSION['paciente']['primer_nombre'] = $primer_nombre;
                $_SESSION['paciente']['segundo_nombre'] = $segundo_nombre;
                $_SESSION['paciente']['primer_apellido'] = $primer_apellido;
                $_SESSION['paciente']['segundo_apellido'] = $segundo_apellido;
                $_SESSION['paciente']['telefono'] = $telefono;
                $_SESSION['paciente']['correo'] = $correo;
            }
        } else {
            $_SESSION['mensaje'] = "error|Error al actualizar datos: " . $conexion->error;
        }
        $stmt->close();
    } elseif (isset($_POST['cambiar_password'])) {
        // Cambiar contraseña (usando MD5 como solicitaste)
        $password_actual = $_POST['password_actual'];
        $nueva_password = $_POST['nueva_password'];
        $confirmar_password = $_POST['confirmar_password'];

        // Validaciones básicas
        if (empty($password_actual) || empty($nueva_password) || empty($confirmar_password)) {
            $_SESSION['mensaje'] = "error|Todos los campos son obligatorios";
            header("Location: config.php");
            exit();
        }

        if ($nueva_password !== $confirmar_password) {
            $_SESSION['mensaje'] = "error|Las contraseñas nuevas no coinciden";
            header("Location: config.php");
            exit();
        }

        if (strlen($nueva_password) < 8) {
            $_SESSION['mensaje'] = "error|La contraseña debe tener al menos 8 caracteres";
            header("Location: config.php");
            exit();
        }

        // Obtener ID de usuario de la sesión
        $id_usuario = $_SESSION['id_usuario'];

        // Verificar contraseña actual con MD5
        $query = "SELECT contraseña FROM usuarios WHERE id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();

        if (md5($password_actual) !== $user_data['contraseña']) {
            $_SESSION['mensaje'] = "error|Contraseña actual incorrecta";
            header("Location: config.php");
            exit();
        }

        // Actualizar contraseña con MD5
        $nueva_password_md5 = md5($nueva_password);
        $update_query = "UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($update_query);
        $stmt->bind_param("si", $nueva_password_md5, $id_usuario);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "success|Contraseña actualizada correctamente";
        } else {
            $_SESSION['mensaje'] = "error|Error al actualizar contraseña: " . $conexion->error;
        }
        $stmt->close();
    }

    header('Location: config.php');
    exit();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Usuario - IPSFANB Dental</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include '../../components/dashboard/sidebar.php'; ?>

        <!-- Contenido principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="container mx-auto px-6 py-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                        Configuración de Usuario
                    </h1>
                </div>

                <!-- Mostrar mensajes -->
                <?php if (isset($_SESSION['mensaje'])):
                    $mensaje = explode("|", $_SESSION['mensaje']);
                    $tipo = $mensaje[0];
                    $texto = $mensaje[1];
                    unset($_SESSION['mensaje']);
                ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: '<?= $tipo ?>',
                                title: '<?= ucfirst($tipo) ?>',
                                text: '<?= $texto ?>',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#2563eb'
                            });
                        });
                    </script>
                <?php endif; ?>

                <!-- Tarjetas de configuración -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información del usuario -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Información Personal</h2>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                <?= $_SESSION['tipo_usuario'] === 'Empleado' ? 
                                    ($_SESSION['empleado']['rol'] ?? 'Empleado') : 'Paciente' ?>
                            </span>
                        </div>

                        <form method="POST" action="config.php">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre*</label>
                                        <input type="text" name="primer_nombre" required
                                            value="<?= htmlspecialchars($usuario['primer_nombre'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                                        <input type="text" name="segundo_nombre"
                                            value="<?= htmlspecialchars($usuario['segundo_nombre'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido*</label>
                                        <input type="text" name="primer_apellido" required
                                            value="<?= htmlspecialchars($usuario['primer_apellido'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                                        <input type="text" name="segundo_apellido"
                                            value="<?= htmlspecialchars($usuario['segundo_apellido'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono*</label>
                                        <input type="tel" name="telefono" required
                                            value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                                        <input type="email" name="correo"
                                            value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <?php if ($_SESSION['tipo_usuario'] === 'Paciente'): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Cédula</label>
                                            <input type="text" readonly
                                                value="<?= htmlspecialchars($usuario['cedula'] ?? '') ?>"
                                                class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Nacimiento</label>
                                            <input type="text" readonly
                                                value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>"
                                                class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md">
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="pt-4">
                                    <button type="submit" name="actualizar_datos"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2 text-white">
                                            <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                        </svg>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Cambiar contraseña -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Cambiar Contraseña</h2>

                        <form method="POST" action="config.php">
                            <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual*</label>
                                    <input type="password" name="password_actual" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña*</label>
                                    <input type="password" name="nueva_password" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña*</label>
                                    <input type="password" name="confirmar_password" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="pt-4">
                                    <button type="submit" name="cambiar_password"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2 text-white">
                                            <path fill-rule="evenodd" d="M15.97 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H7.5a.75.75 0 0 1 0-1.5h11.69l-3.22-3.22a.75.75 0 0 1 0-1.06Zm-7.94 9a.75.75 0 0 1 0 1.06l-3.22 3.22H16.5a.75.75 0 0 1 0 1.5H4.81l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                        </svg>
                                        Cambiar Contraseña
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>