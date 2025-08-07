<?php
// Conexión a la base de datos
include '../../controllers/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Iniciar sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener el id_usuario de la solicitud
$data = json_decode(file_get_contents('php://input'));
$id_usuario = $data->id_usuario;

// Verificar si el id_usuario es válido
if ($id_usuario) {
    // Iniciar una transacción para asegurar que ambas operaciones se realicen correctamente
    $conn->begin_transaction();

    try {
        // Consulta para eliminar el usuario de la tabla jefes_manzanas
        $query_delete = "DELETE FROM jefes_manzanas WHERE id_usuario = ?";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->bind_param("i", $id_usuario);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Consulta para actualizar el tipo en la tabla tipo_usuario
        $query_update = "UPDATE tipo_usuario SET tipo = 'Habitante' WHERE id_usuario = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("i", $id_usuario);
        $stmt_update->execute();
        $stmt_update->close();

        // Registrar la acción en la bitácora
        $accion = "Eliminar Jefe de Manzana y Actualizar Tipo de Usuario";
        $descripcion = "El usuario con ID $id_usuario ha sido eliminado como Jefe de Manzana y su tipo de usuario ha sido actualizado a 'Habitante'."; // Personaliza la descripción

        // Insertar en la tabla de la bitácora
        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
        if ($logStmt = $conn->prepare($logQuery)) {
            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
            $logStmt->execute();
            $logStmt->close();
        }

        // Confirmar la transacción
        $conn->commit();

        // Retornar éxito
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // En caso de error, hacer rollback
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
}

// Cerrar la conexión
$conn->close();
?>
