<?php
include '../../controllers/conexion.php'; // Incluye la conexión a la base de datos

if (isset($_GET['id_manzana'])) {
    $id_manzana = intval($_GET['id_manzana']);
    
    // Consulta para obtener todas las casas con la nueva vista 'datos_casas'
    $query = "SELECT nro_manzana, nro_casa, descripcion_casa, estado_casa, nombre_familia, nombre 
              FROM datos_casas 
              WHERE id_manzana = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_manzana);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $datos = [];
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    $stmt->close();
    $conn->close();
    
    echo json_encode($datos); // Devuelve los datos en formato JSON
} else {
    echo json_encode(['error' => 'No se proporcionó un id_manzana válido']);
}
?>
