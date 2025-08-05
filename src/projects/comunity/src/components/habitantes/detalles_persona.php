<?php
include '../../controladores/conexion.php'; 


// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die(json_encode(array("error" => "Conexión fallida: " . $conn->connect_error)));
}

// Verificar que el parámetro id_persona esté presente
$idPersona = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;
if ($idPersona === null) {
    die(json_encode(array("error" => "El parámetro id_persona no se ha proporcionado.")));
}

// Consulta SQL para obtener los detalles de la persona
$query = "SELECT `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `documento_identidad`,
`codigo_carnet_patria`, `serial_carnet_patria`, `genero`, `fecha_nacimiento`, `cantidad_hijos`, `telefono`, `correo`, `fecha_registro`
FROM `personas` WHERE id_persona = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die(json_encode(array("error" => "Error al preparar la consulta: " . $conn->error)));
}

$stmt->bind_param("i", $idPersona);
$stmt->execute();

$result = $stmt->get_result();

// Comprobar si se encontraron resultados
if ($result->num_rows > 0) {
    $persona = $result->fetch_assoc();
    echo json_encode($persona);
} else {
    // Si no se encuentra la persona, responder con un mensaje claro
    echo json_encode(array("error" => "No se encontró ninguna persona con el ID proporcionado."));
}

$stmt->close();
$conn->close();
?>
