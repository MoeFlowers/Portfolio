<?php
include '../../src/controladores/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de personas
$sql = "SELECT COUNT(id_casa) AS total_casas FROM casas";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_casas = $row['total_casas'];
} else {
    $total_casas = "No se encontraron resultados.";
}

// Cerrar conexión
$conn->close();
?>