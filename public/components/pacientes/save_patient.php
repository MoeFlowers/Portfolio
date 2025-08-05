<?php
require_once '../../database.php';
session_start();

if (!isset($_SESSION['tipo_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

// Recibir todos los datos del formulario
$id_paciente = $_POST['id_paciente'] ?? 0;
$primer_nombre = $_POST['primer_nombre'];
$segundo_nombre = $_POST['segundo_nombre'] ?? '';
$primer_apellido = $_POST['primer_apellido'];
$segundo_apellido = $_POST['segundo_apellido'] ?? '';
$cedula = $_POST['cedula'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$alergias = $_POST['alergias'] ?? '';

$conexion = getDBConnection();

try {
    if ($id_paciente > 0) {
        // Actualizar paciente existente
        $query = "UPDATE pacientes SET 
                 primer_nombre = ?, 
                 segundo_nombre = ?, 
                 primer_apellido = ?, 
                 segundo_apellido = ?, 
                 cedula = ?, 
                 telefono = ?, 
                 correo = ?, 
                 direccion = ?, 
                 alergias = ? 
                 WHERE id_paciente = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssssssssi", 
            $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido,
            $cedula, $telefono, $correo, $direccion, $alergias, $id_paciente);
        $action = 'actualizado';
    } else {
        // Crear nuevo paciente
        $query = "INSERT INTO pacientes 
                 (primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, 
                  cedula, telefono, correo, direccion, alergias) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssssssss", 
            $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido,
            $cedula, $telefono, $correo, $direccion, $alergias);
        $action = 'creado';
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Paciente $action correctamente"]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error al $action paciente"]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}

$stmt->close();
$conexion->close();
?>