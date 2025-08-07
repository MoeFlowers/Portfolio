<?php
include '../../controllers/conexion.php'; // Conexión a la base de datos

// Obtener las manzanas
$consulta_manzanas = "SELECT id_manzana, nro_manzana FROM manzanas";
$resultado_manzanas = $conn->query($consulta_manzanas);

// Obtener los grupos familiares
$consulta_grupos_familiares = "SELECT id_grupo_familiar, id_familia FROM grupos_familiares";
$resultado_grupos_familiares = $conn->query($consulta_grupos_familiares);

// Obtener los nombres de las familias
$consulta_familias = "SELECT id_familia, nombre_familia FROM familias";
$resultado_familias = $conn->query($consulta_familias);

// Crear arrays para las opciones de manzanas y grupos familiares
$manzanas = [];
while ($manzana = $resultado_manzanas->fetch_assoc()) {
    $manzanas[] = $manzana;
}

$grupos_familiares = [];
while ($grupo = $resultado_grupos_familiares->fetch_assoc()) {
    // Obtener el nombre de la familia
    $id_familia = $grupo['id_familia'];
    $familia_query = "SELECT nombre_familia FROM familias WHERE id_familia = $id_familia";
    $familia_result = $conn->query($familia_query);
    $familia = $familia_result->fetch_assoc();

    $grupo['nombre_familia'] = $familia['nombre_familia'];
    $grupos_familiares[] = $grupo;
}

// Enviar los datos como JSON
echo json_encode(['manzanas' => $manzanas, 'grupos_familiares' => $grupos_familiares]);

$conn->close();
?>
