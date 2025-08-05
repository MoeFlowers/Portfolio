<?php
session_start();
require_once '../../components/database/database.php';

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$conn = getDBConnection();

$sql = "SELECT * FROM pacientes ";
$result = $conn->query($sql);

if ($result) {
    $pacientes = [];
    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
    echo json_encode([
        'success' => true,
        'pacientes' => $pacientes
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener pacientes: ' . $conn->error
    ]);
}

$conn->close();
?>