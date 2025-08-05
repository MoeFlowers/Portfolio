<?php
header('Content-Type: application/json');
require_once '../../components/database/database.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Método no permitido. Use POST']));
}

// Obtener y validar datos
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$estado = $_POST['estado'] ?? '';

if (!$id || $id < 1) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'ID de empleado inválido']));
}

if (!in_array($estado, ['Activo', 'Inactivo'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Estado no válido']));
}

try {
    $conn = getDBConnection();
    
    // Verificar que el empleado existe
    $stmtCheck = $conn->prepare("SELECT id_empleado FROM empleados WHERE id_empleado = ?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows === 0) {
        http_response_code(404);
        die(json_encode(['success' => false, 'message' => 'Empleado no encontrado']));
    }
    
    // Actualizar estado
    $stmtUpdate = $conn->prepare("UPDATE empleados SET estado = ? WHERE id_empleado = ?");
    $stmtUpdate->bind_param("si", $estado, $id);
    
    if ($stmtUpdate->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'nuevo_estado' => $estado
        ]);
    } else {
        throw new Exception("Error al actualizar estado");
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) $conn->close();
}
?>