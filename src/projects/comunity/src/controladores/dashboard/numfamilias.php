<?php
include '../../src/controladores/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de personas
$sql = "SELECT COUNT(id_familia) AS total_familias FROM familias";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_familias = $row['total_familias'];
} else {
    $total_familias = "No se encontraron resultados.";
}

// Cerrar conexión
$conn->close();
?>