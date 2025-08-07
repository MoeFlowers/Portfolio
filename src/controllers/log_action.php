<?php
function log_action($id_usuario, $accion, $descripcion) {
    include 'conexion.php';
    $sql = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id_usuario, $accion, $descripcion);
    $stmt->execute();
    $stmt->close();
}
?>
