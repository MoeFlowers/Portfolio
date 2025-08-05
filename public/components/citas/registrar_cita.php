<?php
session_start();
require_once '../../components/database/database.php';

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar campos obligatorios
    $required_fields = ['id_paciente', 'id_empleado', 'id_procedimiento', 'fecha', 'hora'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        $_SESSION['error_message'] = 'Por favor completa todos los campos obligatorios.';
        header("Location: ../../views/citas/citas.php");
        exit;
    }

    // Obtener y sanitizar datos
    $id_paciente = intval($_POST['id_paciente']);
    $id_empleado = intval($_POST['id_empleado']);
    $id_procedimiento = intval($_POST['id_procedimiento']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = $_POST['estado'] ?? 'Programada';
    $observaciones = !empty($_POST['observaciones']) ? trim($_POST['observaciones']) : null;

    // Combinar fecha y hora
    $fecha_hora = date('Y-m-d H:i:s', strtotime("$fecha $hora"));

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("INSERT INTO citas (id_paciente, id_empleado, id_procedimiento, fecha_hora, estado, observaciones) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    
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
        $_SESSION['success_message'] = 'Cita registrada correctamente.';
    } else {
        $_SESSION['error_message'] = 'Error al guardar la cita: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    
    header("Location: ../../views/citas/citas.php");
    exit;
}