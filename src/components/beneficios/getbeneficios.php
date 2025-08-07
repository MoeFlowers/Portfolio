<?php
include '../../controllers/conexion.php';

// Consulta SQL para obtener los beneficios registrados
$sql = "SELECT nombre_beneficio, fecha_suministrado FROM beneficios";
$result = $conn->query($sql);

// Crear un array para almacenar los beneficios
$benefits = array();

// Verificar si la consulta devuelve resultados
if ($result->num_rows > 0) {
    // Agregar cada beneficio al array
    while($row = $result->fetch_assoc()) {
        // Verificar si la fecha está vacía antes de convertirla
        $fecha_suministrado = '';
        if (!empty($row['fecha_suministrado']) && $row['fecha_suministrado'] != '0000-00-00') {
            // Convertir la fecha a formato d-m-Y
            $fecha_suministrado = date('d-m-Y', strtotime($row['fecha_suministrado']));
        }

        $benefits[] = array(
            'nombre_beneficio' => $row['nombre_beneficio'],
            'fecha_suministrados' => $fecha_suministrado
        );
    }

    // Devolver los beneficios como JSON
    echo json_encode($benefits);
} else {
    echo json_encode(array('message' => 'No hay beneficios registrados'));
}

$conn->close();
?>
