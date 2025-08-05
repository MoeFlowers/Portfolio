<?php
// guardar_notas.php

header("Content-Type: application/json");

require_once '../../components/database/database.php';

$conn = getDBConnection();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_paciente'])) {
    echo json_encode(['success' => false, 'error' => 'ID de paciente no proporcionado']);
    exit;
}

$id_paciente = intval($data['id_paciente']);
$antecedentes = isset($data['antecedentes_medicos']) ? trim($data['antecedentes_medicos']) : '';
$observaciones_dentales = isset($data['observaciones_dentales']) ? trim($data['observaciones_dentales']) : '';

// Validación simple
if ($id_paciente <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID de paciente inválido']);
    exit;
}

// Preparar consulta SQL
$sql = "UPDATE pacientes 
        SET antecedentes = ?, observaciones_dentales = ?
        WHERE id_paciente = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $antecedentes, $observaciones_dentales, $id_paciente);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al guardar las notas: ' . $conn->error]);
}
?>