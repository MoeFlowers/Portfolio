<?php
// Incluir la conexión a la base de datos
include '../../controllers/conexion.php';

// Iniciar la sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener los parámetros POST
$id_persona = $_POST['id_persona'];
$id_familia = $_POST['id_familia'];

// Comprobar si se reciben los datos
if (isset($id_persona) && isset($id_familia)) {
    // Consulta para borrar al miembro de la tabla 'grupos_familiares'
    $sql = "DELETE FROM grupos_familiares WHERE id_persona = '$id_persona' AND id_familia = '$id_familia'";

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        // Registrar la acción en la bitácora
        $accion = "Eliminar Persona de Familia";
        $descripcion = "Se eliminó la persona con ID: $id_persona del grupo familiar con ID: $id_familia"; // Personaliza la descripción

        // Insertar en la tabla de la bitácora
        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
        if ($logStmt = $conn->prepare($logQuery)) {
            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
            $logStmt->execute();
            $logStmt->close();
        }

        // Si la eliminación fue exitosa, devolver respuesta en formato JSON
        echo json_encode(['success' => true]);
    } else {
        // Si hubo un error, devolver un mensaje de error
        echo json_encode(['success' => false, 'message' => 'Hubo un problema al eliminar el miembro.']);
    }
} else {
    // Si no se reciben los datos, devolver un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}

// Cerrar la conexión
$conn->close();
?>
