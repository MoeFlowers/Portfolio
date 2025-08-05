<?php
include '../../controladores/conexion.php';

// Verificar si se enviaron datos mediante POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados
    $nombre_beneficio = isset($_POST['nombre_beneficio']) ? $_POST['nombre_beneficio'] : '';
    $fecha_suministrado = isset($_POST['fecha_suministrado']) ? $_POST['fecha_suministrado'] : '';

    // Validar que no estén vacíos
    if (empty($nombre_beneficio) || empty($fecha_suministrado)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Insertar el beneficio en la base de datos
    $sql = "INSERT INTO beneficios (nombre_beneficio, fecha_suministrado) VALUES ('$nombre_beneficio', '$fecha_suministrado')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Beneficio registrado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el beneficio: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
