<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../components/database/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar que todos los campos requeridos estén presentes
$requiredFields = ['id_paciente', 'primer_nombre', 'primer_apellido', 'cedula', 'telefono', 'tipo_sangre']; // Agregado tipo_sangre
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "El campo '$field' es requerido"]);
        exit;
    }
}

// Sanitizar los datos de entrada
$id_paciente = filter_var($_POST['id_paciente'], FILTER_SANITIZE_NUMBER_INT);
$primer_nombre = htmlspecialchars(trim($_POST['primer_nombre']), ENT_QUOTES, 'UTF-8');
$segundo_nombre = isset($_POST['segundo_nombre']) ? htmlspecialchars(trim($_POST['segundo_nombre']), ENT_QUOTES, 'UTF-8') : null;
$primer_apellido = htmlspecialchars(trim($_POST['primer_apellido']), ENT_QUOTES, 'UTF-8');
$segundo_apellido = isset($_POST['segundo_apellido']) ? htmlspecialchars(trim($_POST['segundo_apellido']), ENT_QUOTES, 'UTF-8') : null;
$cedula = htmlspecialchars(trim($_POST['cedula']), ENT_QUOTES, 'UTF-8');
$telefono = htmlspecialchars(trim($_POST['telefono']), ENT_QUOTES, 'UTF-8');
$tipo_sangre = htmlspecialchars(trim($_POST['tipo_sangre']), ENT_QUOTES, 'UTF-8'); // Nuevo campo
$correo = isset($_POST['correo']) ? filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL) : null;
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
$genero = isset($_POST['genero']) ? htmlspecialchars(trim($_POST['genero']), ENT_QUOTES, 'UTF-8') : null;
$direccion = isset($_POST['direccion']) ? htmlspecialchars(trim($_POST['direccion']), ENT_QUOTES, 'UTF-8') : null;
$alergias = isset($_POST['alergias']) ? htmlspecialchars(trim($_POST['alergias']), ENT_QUOTES, 'UTF-8') : null;

// Validar estado
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : 'Activo';
if (!in_array($estado, ['Activo', 'Inactivo'])) {
    echo json_encode(['success' => false, 'message' => 'Estado inválido. Debe ser "Activo" o "Inactivo"']);
    exit;
}

// Validar tipo de sangre
$tiposValidos = ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'];
if (!in_array($tipo_sangre, $tiposValidos)) {
    echo json_encode(['success' => false, 'message' => 'Tipo de sangre inválido']);
    exit;
}

// Validar formato de cédula
if (!preg_match('/^[0-9]{6,10}$/', $cedula)) {
    echo json_encode(['success' => false, 'message' => 'Formato de cédula inválido']);
    exit;
}

// Validar teléfono
if (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
    echo json_encode(['success' => false, 'message' => 'Formato de teléfono inválido']);
    exit;
}

// Validar correo si está presente
if ($correo && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
    exit;
}

// Validar fecha de nacimiento si está presente
if ($fecha_nacimiento) {
    $fecha_nac = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    if (!$fecha_nac || $fecha_nac->format('Y-m-d') !== $fecha_nacimiento) {
        echo json_encode(['success' => false, 'message' => 'Fecha de nacimiento inválida']);
        exit;
    }
}

try {
    $conn = getDBConnection();

    // Preparar consulta con el campo 'tipo_sangre'
    $stmt = $conn->prepare("UPDATE pacientes SET 
                            cedula = ?,
                            primer_nombre = ?,
                            segundo_nombre = ?,
                            primer_apellido = ?,
                            segundo_apellido = ?,
                            fecha_nacimiento = ?,
                            genero = ?,
                            telefono = ?,
                            correo = ?,
                            direccion = ?,
                            alergias = ?,
                            tipo_sangre = ?,  -- Nuevo campo
                            estado = ? 
                            WHERE id_paciente = ?");

    $stmt->bind_param("sssssssssssssi",  // Añadida una 's' más para tipo_sangre
        $cedula,
        $primer_nombre,
        $segundo_nombre,
        $primer_apellido,
        $segundo_apellido,
        $fecha_nacimiento,
        $genero,
        $telefono,
        $correo,
        $direccion,
        $alergias,
        $tipo_sangre,  // Nuevo parámetro
        $estado,
        $id_paciente
    );

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Paciente actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se realizaron cambios o el paciente no existe']);
        }
    } else {
        if ($conn->errno == 1062) {
            echo json_encode(['success' => false, 'message' => 'Error: La cédula o correo ya están registrados']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $conn->error]);
        }
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}