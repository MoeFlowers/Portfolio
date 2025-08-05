<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../components/database/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar campos requeridos
$requiredFields = ['id_empleado', 'primer_nombre', 'primer_apellido', 'cedula', 'telefono', 'correo', 'rol', 'estado'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "El campo '$field' es requerido"]);
        exit;
    }
}

// Sanitizar datos de entrada
$id_empleado = filter_var($_POST['id_empleado'], FILTER_SANITIZE_NUMBER_INT);
$primer_nombre = htmlspecialchars(trim($_POST['primer_nombre']), ENT_QUOTES, 'UTF-8');
$segundo_nombre = isset($_POST['segundo_nombre']) ? htmlspecialchars(trim($_POST['segundo_nombre']), ENT_QUOTES, 'UTF-8') : null;
$primer_apellido = htmlspecialchars(trim($_POST['primer_apellido']), ENT_QUOTES, 'UTF-8');
$segundo_apellido = isset($_POST['segundo_apellido']) ? htmlspecialchars(trim($_POST['segundo_apellido']), ENT_QUOTES, 'UTF-8') : null;
$cedula = htmlspecialchars(trim($_POST['cedula']), ENT_QUOTES, 'UTF-8');
$telefono = htmlspecialchars(trim($_POST['telefono']), ENT_QUOTES, 'UTF-8');
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$rol = htmlspecialchars(trim($_POST['rol']), ENT_QUOTES, 'UTF-8');
$estado = htmlspecialchars(trim($_POST['estado']), ENT_QUOTES, 'UTF-8');
$especialidad = ($rol === 'Dentista' && isset($_POST['especialidad'])) 
    ? htmlspecialchars(trim($_POST['especialidad']), ENT_QUOTES, 'UTF-8') 
    : null;

// Validaciones específicas
if (!preg_match('/^[0-9]{7,10}$/', $cedula)) {
    echo json_encode(['success' => false, 'message' => 'La cédula debe tener entre 7 y 10 dígitos']);
    exit;
}

if (!preg_match('/^[0-9]{10,15}$/', $telefono)) {
    echo json_encode(['success' => false, 'message' => 'El teléfono debe tener entre 10 y 15 dígitos']);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
    exit;
}

// Validar roles permitidos
$rolesPermitidos = ['Dentista', 'Asistente', 'Administrativo'];
if (!in_array($rol, $rolesPermitidos)) {
    echo json_encode(['success' => false, 'message' => 'Rol no válido']);
    exit;
}

// Validar estado permitido
$estadosPermitidos = ['Activo', 'Inactivo'];
if (!in_array($estado, $estadosPermitidos)) {
    echo json_encode(['success' => false, 'message' => 'Estado no válido']);
    exit;
}

// Validar especialidad si es dentista
if ($rol === 'Dentista') {
    $especialidadesPermitidas = [
        'Dentista general',
        'Odontopediatra o dentista pediátrico',
        'Ortodoncista',
        'Periodoncista o especialista en encías',
        'Endodoncista o especialista en tratamientos de conducto',
        'Patólogo oral o cirujano oral',
        'Prostodoncista'
    ];
    
    if (empty($especialidad) || !in_array($especialidad, $especialidadesPermitidas)) {
        echo json_encode(['success' => false, 'message' => 'Especialidad no válida para dentistas']);
        exit;
    }
}

try {
    $conn = getDBConnection();

    // Obtener el rol actual del empleado
    $stmtRolActual = $conn->prepare("SELECT rol FROM empleados WHERE id_empleado = ?");
    $stmtRolActual->bind_param("i", $id_empleado);
    $stmtRolActual->execute();
    $stmtRolActual->bind_result($rol_actual);
    $stmtRolActual->fetch();
    $stmtRolActual->close();

    // Validar si intenta cambiar de Asistente/Administrativo a Dentista
    if (($rol_actual === 'Asistente' || $rol_actual === 'Administrativo') && $rol === 'Dentista') {
        echo json_encode(['success' => false, 'message' => 'El rol de Asistente/Administrativo no puede ser cambiado a Dentista']);
        $conn->close();
        exit;
    }

    // Verificar si la cédula ya existe (excluyendo al empleado actual)
    $stmtVerificar = $conn->prepare("SELECT id_empleado FROM empleados WHERE cedula = ? AND id_empleado != ?");
    $stmtVerificar->bind_param("si", $cedula, $id_empleado);
    $stmtVerificar->execute();
    $stmtVerificar->store_result();
    
    if ($stmtVerificar->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'La cédula ya está registrada para otro empleado']);
        $stmtVerificar->close();
        $conn->close();
        exit;
    }
    $stmtVerificar->close();

    // Preparar consulta de actualización con el campo estado
    $sql = "UPDATE empleados SET 
            cedula = ?,
            primer_nombre = ?,
            segundo_nombre = ?,
            primer_apellido = ?,
            segundo_apellido = ?,
            rol = ?,
            especialidad = ?,
            telefono = ?,
            correo = ?,
            estado = ?
            WHERE id_empleado = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssi", 
        $cedula,
        $primer_nombre,
        $segundo_nombre,
        $primer_apellido,
        $segundo_apellido,
        $rol,
        $especialidad,
        $telefono,
        $correo,
        $estado,
        $id_empleado
    );

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Empleado actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se realizaron cambios o el empleado no existe']);
        }
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>