<?php
// Configuración de la conexión MySQLi
include '../../controllers/conexion.php';

// Iniciar sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Verificar si el nombre de la familia fue enviado
if (isset($_POST['nombre_familia'])) {
    $nombre_familia = $conn->real_escape_string($_POST['nombre_familia']);  // Sanitizar el nombre de la familia

    // Crear la consulta SQL para insertar el nombre de la familia
    $sql = "INSERT INTO familias (nombre_familia) VALUES ('$nombre_familia')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Registrar la acción en la bitácora
        $accion = "Registrar Familia";
        $descripcion = "Se registró una nueva familia con nombre: $nombre_familia"; // Personaliza la descripción

        // Insertar en la tabla de la bitácora
        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
        if ($logStmt = $conn->prepare($logQuery)) {
            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
            $logStmt->execute();
            $logStmt->close();
        }

        echo "Familia registrada exitosamente.";
    } else {
        echo "Error al registrar la familia: " . $conn->error;
    }
} else {
    echo "No se ha enviado el nombre de la familia.";
}

// Cerrar la conexión
$conn->close();
?>