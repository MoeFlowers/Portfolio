<?php

// Conectar a la base de datos
include '../../controllers/conexion.php';

// Iniciar la sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener los datos enviados en el formulario
$id_persona = $_POST['id_persona'];
$id_familia = $_POST['id_familia'];
$parentesco = $_POST['parentesco'];
$jefe_familia = $_POST['jefe_familia'];

// Insertar en grupos_familiares
$query = "INSERT INTO grupos_familiares (id_familia, id_persona, parentesco, jefe_familia) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiss', $id_familia, $id_persona, $parentesco, $jefe_familia);

if ($stmt->execute()) {
    // Registrar la acción en la bitácora
    $accion = "Asignar Persona a Familia";
    $descripcion = "Se asignó la persona con ID: $id_persona al grupo familiar con ID: $id_familia"; // Personaliza la descripción

    // Insertar en la tabla de la bitácora
    $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    if ($logStmt = $conn->prepare($logQuery)) {
        $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
        $logStmt->execute();
        $logStmt->close();
    }

    echo "Persona asignada exitosamente.";
} else {
    echo "Error al asignar persona.";
}

$stmt->close();
$conn->close();
?>
