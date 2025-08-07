<?php
include '../../controllers/conexion.php';

// Obtener los habitantes (personas) desde la vista
$query_personas = "SELECT id_usuario, nombre_y_apellido FROM datos_habitantes";
$result_personas = mysqli_query($conn, $query_personas);
$personas = [];
while ($row = mysqli_fetch_assoc($result_personas)) {
    $personas[] = $row;
}

// Obtener las manzanas disponibles
$query_manzanas = "SELECT id_manzana, nro_manzana FROM manzanas";
$result_manzanas = mysqli_query($conn, $query_manzanas);
$manzanas = [];
while ($row = mysqli_fetch_assoc($result_manzanas)) {
    $manzanas[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode(['personas' => $personas, 'manzanas' => $manzanas]);
?>
