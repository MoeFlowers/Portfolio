<?php
ob_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../components/database/database.php';

$response = [
    'success' => false,
    'message' => 'Error desconocido',
    'historial' => [],
    'has_history' => false
];

try {
    // Validar ID
    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
        throw new Exception("ID inválido", 400);
    }

    $id_paciente = (int)$_GET['id'];
    $conn = getDBConnection();

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión a la BD", 500);
    }

    // Consulta para verificar si existe historial
    $check_sql = "SELECT COUNT(*) as total FROM historias_clinicas WHERE id_paciente = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id_paciente);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $has_history = $check_result->fetch_assoc()['total'] > 0;

    $response['has_history'] = $has_history;

    // Si tiene historial, obtenerlo
    if ($has_history) {
        $sql = "SELECT 
                    id_historia,
                    fecha_consulta as fecha,
                    motivo_consulta as titulo,
                    diagnostico,
                    plan_tratamiento as tratamiento,
                    observaciones,
                    estado
                FROM historias_clinicas 
                WHERE id_paciente = ?
                ORDER BY fecha_consulta DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $conn->error, 500);
        }

        $stmt->bind_param("i", $id_paciente);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error, 500);
        }

        $result = $stmt->get_result();
        $historial = [];

        while ($row = $result->fetch_assoc()) {
            $historial[] = $row;
        }

        $response['historial'] = $historial;
    }

    $response['success'] = true;
    $response['message'] = $has_history ? 'Historial encontrado' : 'El paciente no tiene historial registrado';
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    $response['message'] = $e->getMessage();

    // Registrar error
    error_log("ERROR en get_patient_history.php: " . $e->getMessage());
} finally {
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();

    ob_end_clean();
    echo json_encode($response);
    exit;
}
