<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Iniciar Sesión</title>
    <link rel="icon" href="../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="../../src/utils/output.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/css/all.min.css">
</head>

<body class="min-h-screen flex items-center">
    <!-- Fondo con imagen odontológica -->
    <div class="fixed inset-0 -z-10">
        <img src="../assets/images/bg.jpg" alt="Fondo odontológico" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-cyan-50 mix-blend-overlay"></div>
    </div>
    <!-- Contenedor principal -->
    <div class="max-w-md mx-auto p-8 bg-gray-100 rounded-xl shadow-2xl w-full backdrop-blur-sm">
        <div class="text-center mb-8">
            <img src="../assets/images/logo-removebg-preview.png" alt="IPSFANB Logo" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-blue-800">Acceso al Sistema</h1>
            <p class="text-gray-600">Ingrese sus credenciales para acceder al historial clínico</p>
        </div>

        <!-- Formulario de inicio de sesión -->
        <?php include_once '../components/login/formulario.php'; ?>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">¿Nuevo en el sistema?</span>
                </div>
            </div>

        </div>
    </div>
    <!--LoginScript-->
    <script src="../assets/js/login.js"></script>
</body>
</html>