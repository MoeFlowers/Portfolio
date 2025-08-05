<?php
header('Content-Type: application/json');
require_once '../../components/database/database.php';

// Verifica si es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Método no permitido']));
}

// Verifica datos recibidos
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'ID inválido']));
}

try {
    $conn = getDBConnection();
    
    // Verifica paciente
    $stmt = $conn->prepare("SELECT id_paciente FROM pacientes WHERE id_paciente = ? AND estado = 'Activo'");
    if (!$stmt) throw new Exception("Error en preparación: " . $conn->error);
    
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) throw new Exception("Error en ejecución: " . $stmt->error);
    
    if ($stmt->get_result()->num_rows === 0) {
        throw new Exception("Paciente no encontrado o ya inactivo", 404);
    }
    
    // Actualiza
    $update = $conn->prepare("UPDATE pacientes SET estado = 'Inactivo' WHERE id_paciente = ?");
    if (!$update) throw new Exception("Error en preparación: " . $conn->error);
    
    $update->bind_param("i", $id);
    if (!$update->execute()) throw new Exception("Error en ejecución: " . $update->error);
    
    echo json_encode([
        'success' => true,
        'message' => 'Paciente desactivado correctamente',
        'id' => $id
    ]);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => $conn->error ?? null
    ]);
} finally {
    if (isset($conn)) $conn->close();
}
?>