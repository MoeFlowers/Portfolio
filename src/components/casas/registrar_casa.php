<?php
include '../../controllers/conexion.php';

// Iniciar la sesión para obtener el ID del usuario
session_start();

// Establecer el encabezado para la respuesta JSON
header('Content-Type: application/json');

// Recibir los datos enviados desde JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Validar que los campos requeridos no estén vacíos
if (empty($data['nro_manzana']) || empty($data['numero_casa']) || empty($data['grupo_familiar']) || empty($data['descripcion_casa']) || empty($data['estado_casa'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
    exit;
}

// Asignar valores a las variables
$nro_manzana = $data['nro_manzana'];
$numero_casa = $data['numero_casa'];
$id_grupo_familiar = $data['grupo_familiar'];
$descripcion_casa = $data['descripcion_casa'];
$estado_casa = $data['estado_casa'];

// Obtener el ID del usuario desde la sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no logueado']);
    exit;
}
$idUsuario = $_SESSION['id_usuario'];

// Consulta SQL para insertar la casa
$sql = "INSERT INTO casas (id_manzana, nro_casa, id_grupo_familiar, descripcion_casa, estado_casa) VALUES (?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Verificar si la preparación fue exitosa
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
    exit;
}

// Enlazar los parámetros
$stmt->bind_param('iiiss', $nro_manzana, $numero_casa, $id_grupo_familiar, $descripcion_casa, $estado_casa);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Registrar la acción en la bitácora
    $accion = "Registrar Casa";
    $descripcion = "Se registró la casa con número: $numero_casa en la manzana: $nro_manzana"; // Personaliza la descripción

    // Insertar en la tabla de la bitácora
    $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    if ($logStmt = $conn->prepare($logQuery)) {
        $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
        $logStmt->execute();
        $logStmt->close();
    }

    // Responder exitosamente
    echo json_encode(['success' => true, 'message' => 'Casa registrada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar la casa']);
}

// Cerrar la consulta y la conexión
$stmt->close();
$conn->close();
?>
