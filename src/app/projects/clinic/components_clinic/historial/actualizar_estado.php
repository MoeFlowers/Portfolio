<?php
session_start();
require_once '../../components/database/database.php';

if (!isset($_SESSION['usuario'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$id_historia = isset($data['id_historia']) ? intval($data['id_historia']) : 0;
$estado = isset($data['estado']) ? $data['estado'] : '';

// Validar estado
$estadosPermitidos = ['Pendiente', 'En proceso', 'Finalizado'];
if (!in_array($estado, $estadosPermitidos)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'error' => 'Estado no válido']);
    exit;
}

// Actualizar en la base de datos
$conn = getDBConnection();
$sql = "UPDATE historias_clinicas SET estado = ? WHERE id_historia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $estado, $id_historia);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>