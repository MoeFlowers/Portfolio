<?php
session_start();
require_once '../../components/database/database.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pacientes.php');
    exit;
}

$id_paciente = $_POST['id_paciente'] ?? 0;
$motivo_consulta = $_POST['motivo_consulta'] ?? '';
$diagnostico = $_POST['diagnostico'] ?? '';
$plan_tratamiento = $_POST['plan_tratamiento'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';
$id_empleado = $_SESSION['id_empleado'] ?? 0; // Asume que el id del empleado está en la sesión

$conn = getDBConnection();

// Insertar nuevo historial
$sql = "INSERT INTO historias_clinicas (
            id_paciente, 
            id_empleado, 
            fecha_consulta, 
            motivo_consulta, 
            diagnostico, 
            plan_tratamiento, 
            observaciones,
            estado
        ) VALUES (?, ?, NOW(), ?, ?, ?, ?, 'Pendiente')";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "iissss", 
    $id_paciente, 
    $id_empleado, 
    $motivo_consulta, 
    $diagnostico, 
    $plan_tratamiento, 
    $observaciones
);

if ($stmt->execute()) {
    header("Location: historial.php?id=$id_paciente&success=1");
} else {
    header("Location: historial.php?id=$id_paciente&error=1");
}

$stmt->close();
$conn->close();