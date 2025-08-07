<?php
include '../../src/controllers/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de personas
$sql = "SELECT COUNT(id_manzana) AS total_manzanas FROM manzanas";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_manzanas = $row['total_manzanas'];
} else {
    $total_manzanas = "No se encontraron resultados.";
}

// Cerrar conexión
$conn->close();
?>