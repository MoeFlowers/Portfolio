<?php
session_start();
require_once '../../components/database/database.php';

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cita = intval($_POST['id_cita']);
    $id_paciente = intval($_POST['id_paciente']);
    $id_empleado = intval($_POST['id_empleado']);
    $id_procedimiento = intval($_POST['id_procedimiento']);
    $fecha = $_POST['fecha_hora'];
    $hora = $_POST['hora_hora'];

    if (!$id_cita || !$id_paciente || !$id_empleado || !$id_procedimiento || !$fecha || !$hora) {
        die("Por favor completa todos los campos obligatorios.");
    }

    $fecha_hora = "$fecha $hora";
    $estado = $_POST['estado'] ?? 'Programada';
    $observaciones = trim($_POST['observaciones'] ?? '');

    // Actualizar cita
    $stmt = $conn->prepare("
        UPDATE citas SET
            id_paciente = ?,
            id_empleado = ?,
            id_procedimiento = ?,
            fecha_hora = ?,
            estado = ?,
            observaciones = ?
        WHERE id_cita = ?
    ");

    $stmt->bind_param(
        "iiisssi",
        $id_paciente,
        $id_empleado,
        $id_procedimiento,
        $fecha_hora,
        $estado,
        $observaciones,
        $id_cita
    );

    if ($stmt->execute()) {
        header("Location: ../../views/citas/citas.php");
        exit;
    } else {
        echo "Error al guardar los cambios: " . $stmt->error;
    }
}
?>