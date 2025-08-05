<?php
session_start();
require_once '../../components/database/database.php'; // Ajusta según tu estructura

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = intval($_POST['id_paciente']);
    $id_empleado = intval($_POST['id_empleado']);
    $id_procedimiento = intval($_POST['id_procedimiento']);
    $fecha = $_POST['fecha_hora'];
    $hora = $_POST['hora_hora'];

    if (!$id_paciente || !$id_empleado || !$id_procedimiento || !$fecha || !$hora) {
        die("Por favor completa todos los campos obligatorios.");
    }

    $fecha_hora = "$fecha $hora"; // Combina fecha y hora

    $stmt = $conn->prepare("INSERT INTO citas (id_paciente, id_empleado, id_procedimiento, fecha_hora, estado, observaciones) VALUES (?, ?, ?, ?, ?, ?)");
    $estado = $_POST['estado'] ?? 'Programada';
    $observaciones = trim($_POST['observaciones']) ?: null;

    $stmt->bind_param(
        "iiisss",
        $id_paciente,
        $id_empleado,
        $id_procedimiento,
        $fecha_hora,
        $estado,
        $observaciones
    );

    if ($stmt->execute()) {
        header("Location: ../../views/citas/citas.php");
        exit;
    } else {
        echo "Error al guardar la cita: " . $stmt->error;
    }
}
?>