<?php
// Conexión a la base de datos
include '../../controllers/conexion.php';

// Iniciar sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Recibir datos del POST
$data = json_decode(file_get_contents('php://input'), true);

$id_usuario = $data['id_usuario'] ?? null;
$status = $data['status'] ?? null;

$response = ['success' => false];

if ($id_usuario && $status) {
    // Actualizar el estado en la tabla usuarios
    $query = "UPDATE usuarios SET status = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id_usuario);

    if ($stmt->execute()) {
        $response['success'] = true;

        // Registrar la acción en la bitácora
        $accion = "Actualizar Estado de Usuario";
        $descripcion = "El usuario con ID $id_usuario ha cambiado su estado a '$status'."; // Personaliza la descripción

        // Insertar en la tabla de la bitácora
        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
        if ($logStmt = $conn->prepare($logQuery)) {
            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
            $logStmt->execute();
            $logStmt->close();
        }
    }
    $stmt->close();
}

// Cerrar la conexión
$conn->close();

// Enviar respuesta
header('Content-Type: application/json');
echo json_encode($response);
?>
