<?php
require_once '../../components/database/database.php';
session_start();

if (!isset($_SESSION['tipo_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

$id_paciente = $_GET['id'] ?? 0;

$conexion = getDBConnection();
$query = "SELECT *, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad FROM pacientes WHERE id_paciente = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $paciente = $result->fetch_assoc();
    echo json_encode(['success' => true, 'paciente' => $paciente]);
} else {
    echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
}

$stmt->close();
$conexion->close();
?>