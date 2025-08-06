<?php
include '../../src/controladores/conexion.php'; // Asegúrate de que la ruta sea correcta

// Consulta para obtener el total de beneficios
$sql = "SELECT COUNT(id_beneficio) AS total_beneficios FROM beneficios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_beneficios = $row['total_beneficios']; // Cambiado a total_beneficios
} else {
    $total_beneficios = "No se encontraron resultados."; // Cambiado a total_beneficios
}

// Cerrar conexión
$conn->close();
?>
