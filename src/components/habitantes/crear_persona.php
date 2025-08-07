<?php
header('Content-Type: application/json');

// Configura tu conexión a la base de datos
include '../../controllers/conexion.php';

try {
    // Validar entrada
    $requiredFields = ['usuario', 'contraseña', 'primer_nombre', 'primer_apellido', 'documento_identidad', 'telefono'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "El campo $field es obligatorio."]);
            exit;
        }
    }

    // Iniciar la sesión
    session_start();
    $idUsuario = $_SESSION['id_usuario'];  // Obtener el ID del usuario que está realizando la acción

    // Iniciar la transacción
    $conn->begin_transaction();

    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'] ?? null;
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'] ?? null;
    $documento_identidad = $_POST['documento_identidad'];
    $codigo_carnet_patria = $_POST['codigo_carnet_patria'] ?? null;
    $serial_carnet_patria = $_POST['serial_carnet_patria'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $cantidad_hijos = $_POST['cantidad_hijos'] ?? null;
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'] ?? null;

    // Insertar el usuario en la tabla usuarios
    $stmt_usuario = $conn->prepare("INSERT INTO usuarios (usuario, contraseña) VALUES (?, ?)");
    $hashedPassword = md5($contraseña);
    $stmt_usuario->bind_param("ss", $usuario, $hashedPassword);

    if (!$stmt_usuario->execute()) {
        throw new Exception('Error al insertar en la tabla usuarios: ' . $stmt_usuario->error);
    }

    // Obtener el último ID insertado (id_usuario)
    $id_usuario = $conn->insert_id;

    // Insertar los datos del habitante en la tabla personas
    $stmt_persona = $conn->prepare("
        INSERT INTO personas (
            id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, documento_identidad, 
            codigo_carnet_patria, serial_carnet_patria, genero, fecha_nacimiento, cantidad_hijos, telefono, correo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt_persona->bind_param(
        "issssssssssss", $id_usuario, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, 
        $documento_identidad, $codigo_carnet_patria, $serial_carnet_patria, $genero, $fecha_nacimiento, 
        $cantidad_hijos, $telefono, $correo
    );

    if (!$stmt_persona->execute()) {
        throw new Exception('Error al insertar en la tabla personas: ' . $stmt_persona->error);
    }

    // Insertar en la tabla tipo_usuario con el id_usuario y tipo por defecto 'Habitante'
    $stmt_tipo_usuario = $conn->prepare("INSERT INTO tipo_usuario (id_usuario, tipo) VALUES (?, ?)");
    $tipo_usuario = 'Habitante';  // Valor por defecto
    $stmt_tipo_usuario->bind_param("is", $id_usuario, $tipo_usuario);

    if (!$stmt_tipo_usuario->execute()) {
        throw new Exception('Error al insertar en la tabla tipo_usuario: ' . $stmt_tipo_usuario->error);
    }

    // Registrar la acción en la bitácora
    $accion = "Registrar Habitante";
    $descripcion = "Se registró un habitante con nombre: $primer_nombre $primer_apellido, usuario: $usuario, documento de identidad: $documento_identidad";

    // Insertar en la bitácora
    $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    if ($logStmt = $conn->prepare($logQuery)) {
        $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
        $logStmt->execute();
        $logStmt->close();
    }

    // Confirmar la transacción
    $conn->commit();

    // Respuesta exitosa
    echo json_encode(['success' => true, 'message' => 'Habitante registrado correctamente']);

} catch (Exception $e) {
    // Deshacer la transacción en caso de error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al registrar el habitante', 'error' => $e->getMessage()]);
} finally {
    // Cerrar las declaraciones y la conexión
    if (isset($stmt_usuario)) $stmt_usuario->close();
    if (isset($stmt_persona)) $stmt_persona->close();
    if (isset($stmt_tipo_usuario)) $stmt_tipo_usuario->close();
    $conn->close();
}
?>
