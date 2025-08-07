<?php
include '../../controllers/conexion.php'; 

// Mostrar todos los errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die(json_encode(array("error" => "Conexión fallida: " . $conn->connect_error)));
}

// Iniciar la sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die(json_encode(array("error" => "Usuario no logueado")));
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener los datos enviados en el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos fueron enviados correctamente
if (empty($data)) {
    die(json_encode(array("error" => "No se enviaron datos")));
}

// Extraer los datos del arreglo
$id_persona = $data['id_persona'];
$primer_nombre = isset($data['primer_nombre']) ? $data['primer_nombre'] : null;
$segundo_nombre = isset($data['segundo_nombre']) ? $data['segundo_nombre'] : null;
$primer_apellido = isset($data['primer_apellido']) ? $data['primer_apellido'] : null;
$segundo_apellido = isset($data['segundo_apellido']) ? $data['segundo_apellido'] : null;
$documento_identidad = isset($data['documento_identidad']) ? $data['documento_identidad'] : null;
$codigo_carnet_patria = isset($data['codigo_carnet_patria']) ? $data['codigo_carnet_patria'] : null;
$serial_carnet_patria = isset($data['serial_carnet_patria']) ? $data['serial_carnet_patria'] : null;
$genero = isset($data['genero']) ? $data['genero'] : null;
$fecha_nacimiento = isset($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null;
$cantidad_hijos = isset($data['cantidad_hijos']) ? $data['cantidad_hijos'] : null;
$telefono = isset($data['telefono']) ? $data['telefono'] : null;
$correo = isset($data['correo']) ? $data['correo'] : null;

// Construir la consulta SQL dinámica
$query = "UPDATE personas SET ";
$params = [];
$types = "";

// Solo agregar los campos que no son nulos a la consulta
if ($primer_nombre !== null) { $query .= "primer_nombre = ?, "; $params[] = $primer_nombre; $types .= "s"; }
if ($segundo_nombre !== null) { $query .= "segundo_nombre = ?, "; $params[] = $segundo_nombre; $types .= "s"; }
if ($primer_apellido !== null) { $query .= "primer_apellido = ?, "; $params[] = $primer_apellido; $types .= "s"; }
if ($segundo_apellido !== null) { $query .= "segundo_apellido = ?, "; $params[] = $segundo_apellido; $types .= "s"; }
if ($documento_identidad !== null) { $query .= "documento_identidad = ?, "; $params[] = $documento_identidad; $types .= "s"; }
if ($codigo_carnet_patria !== null) { $query .= "codigo_carnet_patria = ?, "; $params[] = $codigo_carnet_patria; $types .= "s"; }
if ($serial_carnet_patria !== null) { $query .= "serial_carnet_patria = ?, "; $params[] = $serial_carnet_patria; $types .= "s"; }
if ($genero !== null) { $query .= "genero = ?, "; $params[] = $genero; $types .= "s"; }
if ($fecha_nacimiento !== null) { $query .= "fecha_nacimiento = ?, "; $params[] = $fecha_nacimiento; $types .= "s"; }
if ($cantidad_hijos !== null) { $query .= "cantidad_hijos = ?, "; $params[] = $cantidad_hijos; $types .= "i"; }
if ($telefono !== null) { $query .= "telefono = ?, "; $params[] = $telefono; $types .= "s"; }
if ($correo !== null) { $query .= "correo = ?, "; $params[] = $correo; $types .= "s"; }

// Eliminar la última coma y espacio
$query = rtrim($query, ", ");

// Añadir la condición WHERE
$query .= " WHERE id_persona = ?";

// Añadir el id_persona al final de los parámetros
$params[] = $id_persona;
$types .= "i";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die(json_encode(array("error" => "Error al preparar la consulta: " . $conn->error)));
}

// Vincular los parámetros
$stmt->bind_param($types, ...$params);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Registrar la acción en la bitácora
    $accion = "Actualizar Persona";
    $descripcion = "Se actualizó la persona con ID: $id_persona"; // Personaliza la descripción

    // Insertar en la tabla de la bitácora
    $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    if ($logStmt = $conn->prepare($logQuery)) {
        $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
        $logStmt->execute();
        $logStmt->close();
    }

    echo json_encode(array("success" => true));
} else {
    echo json_encode(array("error" => "Error al actualizar los datos"));
}

$stmt->close();
$conn->close();
?>
