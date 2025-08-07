<?php
include '../../controllers/conexion.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no logueado']);
    exit;
}

// Obtener el ID del usuario desde la sesión
$idUsuario = $_SESSION['id_usuario'];

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
        // Acción para registrar en la bitácora
        $accion = "Registrar Beneficio";
        $descripcion = "Se registró un beneficio con nombre: $nombre_beneficio y fecha suministrado: $fecha_suministrado";

        // Insertar la acción en la tabla de bitácora
        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
        if ($logStmt = $conn->prepare($logQuery)) {
            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
            $logStmt->execute();
            $logStmt->close();
        }

        // Respuesta exitosa
        echo json_encode(['success' => true, 'message' => 'Beneficio registrado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el beneficio: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
