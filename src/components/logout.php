<?php
session_start();  // Reanudar la sesión

// Verificar si la sesión está activa
if (isset($_SESSION['usuario'])) {
    // Incluir la conexión a la base de datos
    include '../../src/controllers/conexion.php';

    // Obtener el id_usuario y el nombre de usuario de la sesión
    $usuario = $_SESSION['usuario'];
    
    // Consultar el id_usuario correspondiente al nombre de usuario
    $sql = "SELECT id_usuario FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // Registrar la acción de cierre de sesión en la bitácora
    $accion = "Cerrar sesión";
    $descripcion = "El usuario $usuario cerró sesión.";

    // Insertar en la bitácora
    $sql = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $accion, $descripcion);
    $stmt->execute();
    $stmt->close();
}

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: ../index.php");
exit();
?>
