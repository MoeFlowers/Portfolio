<?php
include '../../src/controllers/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de personas
$sql = "SELECT COUNT(id_jefe_manzana) AS total_jefes FROM jefes_manzanas";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_jefes = $row['total_jefes'];
} else {
    $total_jefes = "No se encontraron resultados.";
}

// Cerrar conexión
$conn->close();
?>