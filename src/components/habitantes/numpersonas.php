<?php
include '../../src/controllers/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de personas
$sql = "SELECT COUNT(id_persona) AS total_personas FROM personas";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_personas = $row['total_personas'];
} else {
    $total_personas = "No se encontraron resultados.";
}

// Cerrar conexión
$conn->close();
?>