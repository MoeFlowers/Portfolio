<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensaje'] = "error|Debe iniciar sesión para registrar pacientes.";
    header("Location: ../login.php");
    exit;
}

// Incluir conexión a la base de datos
require_once '../../components/database/database.php';
$conn = getDBConnection();

// Validar método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar los datos del formulario
    $primer_nombre = trim($_POST['primer_nombre'] ?? '');
    $segundo_nombre = trim($_POST['segundo_nombre'] ?? '');
    $primer_apellido = trim($_POST['primer_apellido'] ?? '');
    $segundo_apellido = trim($_POST['segundo_apellido'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $alergias = trim($_POST['alergias'] ?? '');
    $tipo_sangre = trim($_POST['tipo_sangre'] ?? ''); // Nuevo campo

    // Validaciones básicas
    if (empty($primer_nombre) || empty($primer_apellido) || empty($cedula) ||
        empty($fecha_nacimiento) || empty($genero) || empty($telefono) || empty($tipo_sangre)) {
        $_SESSION['mensaje'] = "error|Por favor, complete todos los campos obligatorios.";
        header("Location: pacientes.php");
        exit;
    }

    // Validación adicional: cédula numérica y longitud mínima
    if (!is_numeric($cedula) || strlen($cedula) < 7) {
        $_SESSION['mensaje'] = "error|La cédula debe tener al menos 7 dígitos.";
        header("Location: pacientes.php");
        exit;
    }

    // Validación de teléfono (10 dígitos mínimo)
    if (!is_numeric($telefono) || strlen($telefono) < 10) {
        $_SESSION['mensaje'] = "error|El teléfono debe tener al menos 10 dígitos.";
        header("Location: pacientes.php");
        exit;
    }

    // Validación opcional de correo
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensaje'] = "error|Correo electrónico no válido.";
        header("Location: pacientes.php");
        exit;
    }

    // Validación del tipo de sangre
    $tipos_sangre_validos = ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'];
    if (!in_array($tipo_sangre, $tipos_sangre_validos)) {
        $_SESSION['mensaje'] = "error|Tipo de sangre no válido.";
        header("Location: pacientes.php");
        exit;
    }

    // Insertar paciente en la base de datos con estado 'Activo'
    $sql = "INSERT INTO pacientes (
                primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                cedula, fecha_nacimiento, genero, telefono, correo, direccion, 
                alergias, tipo_sangre, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Activo')";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['mensaje'] = "error|Error al preparar la consulta: " . $conn->error;
        header("Location: pacientes.php");
        exit;
    }

    // Asignar parámetros y ejecutar
    $stmt->bind_param(
        "ssssssssssss",
        $primer_nombre,
        $segundo_nombre,
        $primer_apellido,
        $segundo_apellido,
        $cedula,
        $fecha_nacimiento,
        $genero,
        $telefono,
        $correo,
        $direccion,
        $alergias,
        $tipo_sangre
    );

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "success|Paciente registrado correctamente.";
    } else {
        $_SESSION['mensaje'] = "error|Hubo un problema al registrar al paciente: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['mensaje'] = "error|Acceso no permitido.";
}

$conn->close();

header("Location: ../../views/pacientes/pacientes.php");
exit;
?>