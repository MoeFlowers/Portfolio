<?php
session_start();

// Incluir conexión
require_once '../../components/database/database.php';
$conn = getDBConnection();

// Leer datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_paciente'])) {
    echo json_encode(['success' => false, 'error' => 'ID de paciente no proporcionado']);
    exit;
}

$id_paciente = intval($data['id_paciente']);
$dientes = $data['dientes'] ?? [];

// Obtener y sanitizar datos de la historia clínica
$motivo_consulta = isset($data['motivo_consulta']) ? $conn->real_escape_string(substr($data['motivo_consulta'], 0, 100)) : '';
$diagnostico = isset($data['diagnostico']) ? $conn->real_escape_string(substr($data['diagnostico'], 0, 100)) : '';
$plan_tratamiento = isset($data['plan_tratamiento']) ? $conn->real_escape_string(substr($data['plan_tratamiento'], 0, 100)) : '';
$observaciones = isset($data['observaciones']) ? $conn->real_escape_string(substr($data['observaciones'], 0, 100)) : '';

// Determinar el odontólogo (prioridad: datos enviados > sesión > valor por defecto 1)
$id_empleado = isset($data['odontologo']) ? intval($data['odontologo']) : ($_SESSION['usuario']['id_empleado'] ?? 1);

try {
    // 1. Buscar o crear historia clínica
    $stmt = $conn->prepare("SELECT id_historia FROM historias_clinicas WHERE id_paciente = ? ORDER BY fecha_consulta DESC LIMIT 1");
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // No hay historial → crear uno nuevo
        $stmt_insert = $conn->prepare("
            INSERT INTO historias_clinicas (
                id_paciente,
                id_empleado,
                motivo_consulta,
                diagnostico,
                plan_tratamiento,
                observaciones,
                estado
            ) VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')
        ");
        $stmt_insert->bind_param(
            "iissss", 
            $id_paciente, 
            $id_empleado,
            $motivo_consulta,
            $diagnostico,
            $plan_tratamiento,
            $observaciones
        );
        $stmt_insert->execute();
        $id_historia = $stmt_insert->insert_id;
        $stmt_insert->close();
    } else {
        $row = $result->fetch_assoc();
        $id_historia = $row['id_historia'];

        // 2. Actualizar historial existente con los nuevos datos
        $stmt_update = $conn->prepare("
            UPDATE historias_clinicas SET 
                fecha_consulta = NOW(),
                id_empleado = ?,
                motivo_consulta = ?,
                diagnostico = ?,
                plan_tratamiento = ?,
                observaciones = ?,
                estado = CASE WHEN estado = 'Finalizado' THEN 'Finalizado' ELSE 'En proceso' END
            WHERE id_historia = ?
        ");
        $stmt_update->bind_param(
            "issssi",
            $id_empleado,
            $motivo_consulta,
            $diagnostico,
            $plan_tratamiento,
            $observaciones,
            $id_historia
        );
        $stmt_update->execute();
        $stmt_update->close();
    }

    // 3. Guardar los dientes seleccionados (solo si hay dientes para guardar)
    if (!empty($dientes)) {
        foreach ($dientes as $diente) {
            $numero = $conn->real_escape_string($diente['numero'] ?? '');
            $tipo = $conn->real_escape_string($diente['tipo'] ?? '');
            $estado = $conn->real_escape_string($diente['estado'] ?? '');
            $tratamiento = $conn->real_escape_string($diente['tratamiento'] ?? '');
            $notas = $conn->real_escape_string($diente['notas'] ?? '');

            $stmt_insert = $conn->prepare("
                INSERT INTO dientes (
                    id_historia, numero_diente, tipo_diente, estado, tratamiento, observaciones
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt_insert->bind_param(
                "isssss",
                $id_historia,
                $numero,
                $tipo,
                $estado,
                $tratamiento,
                $notas
            );
            $stmt_insert->execute();
            $stmt_insert->close();
        }
    }

    echo json_encode(['success' => true, 'id_historia' => $id_historia]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>