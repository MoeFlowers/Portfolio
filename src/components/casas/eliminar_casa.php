<?php
include '../../controllers/conexion.php'; // Asegúrate de la conexión a la base de datos

// Iniciar la sesión para acceder a la variable de sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no logueado']);
    exit();
}

// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents("php://input"));

// Verificar si se recibió id_casa
if (isset($data->id_casa)) {
    $id_casa = $data->id_casa;

    // Deshabilitar las restricciones de claves foráneas
    $conn->query("SET foreign_key_checks=0");

    // Eliminar la casa de la tabla 'casas'
    $eliminarCasa = "DELETE FROM casas WHERE id_casa = ?";
    $stmt = $conn->prepare($eliminarCasa);
    $stmt->bind_param("i", $id_casa);
    $stmt->execute();

    // Verificar si la eliminación fue exitosa
    if ($stmt->affected_rows > 0) {
        // Obtener el ID del usuario desde la sesión
        $idUsuario = $_SESSION['id_usuario'];

        // Registrar la acción en la bitácora
        $accion = "Eliminar Casa";
        $descripcion = "Eliminó la casa con ID: $id_casa";  // Personaliza la descripción con el ID de la casa eliminada

        // Insertar en la tabla de la bitácora
        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
        if ($logStmt = $conn->prepare($logQuery)) {
            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
            $logStmt->execute();
            $logStmt->close();
        }

        // Responder exitosamente
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la casa']);
    }

    // Cerrar la declaración
    $stmt->close();

    // Reactivar las restricciones de claves foráneas
    $conn->query("SET foreign_key_checks=1");
} else {
    echo json_encode(['success' => false, 'message' => 'id_casa no fue recibido']);
}

// Cerrar la conexión
$conn->close();
?>
