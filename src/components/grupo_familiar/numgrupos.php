<?php
include '../../src/controllers/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de personas
$sql = "SELECT COUNT(DISTINCT id_familia) AS total_grupos FROM datos_familias";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_grupos = $row['total_grupos'];
} else {
    $total_grupos = "No se encontraron resultados.";
}

// Cerrar conexión
$conn->close();
?>