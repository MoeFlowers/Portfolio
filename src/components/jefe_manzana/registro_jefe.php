<?php
include '../../controllers/conexion.php';

// Iniciar sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

$persona = $data['persona'];  // ID del habitante seleccionado (id_usuario)
$manzana = $data['manzana'];  // ID de la manzana seleccionada

// Validar los datos recibidos
if (empty($persona) || empty($manzana)) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

// Iniciar la transacción
mysqli_begin_transaction($conn);

try {
    // Preparar la consulta para insertar el jefe de manzana
    $query_jefe = "INSERT INTO jefes_manzanas (id_usuario, id_tipo_usuario, id_manzana) 
                   VALUES (?, ?, ?)";
    
    // Preparar la sentencia
    $stmt_jefe = $conn->prepare($query_jefe);
    
    if ($stmt_jefe === false) {
        throw new Exception('Error en la preparación de la consulta para jefe de manzana');
    }

    // Vincular los parámetros
    $stmt_jefe->bind_param('iii', $persona, $persona, $manzana);

    // Ejecutar la consulta
    if (!$stmt_jefe->execute()) {
        throw new Exception('Error al registrar el jefe de manzana');
    }

    // Cambiar el tipo de usuario a "Jefe Manzana" en la tabla tipo_usuario
    $query_tipo_usuario = "UPDATE tipo_usuario SET tipo = 'Jefe Manzana' WHERE id_usuario = ?";
    $stmt_tipo = $conn->prepare($query_tipo_usuario);
    
    if ($stmt_tipo === false) {
        throw new Exception('Error en la preparación de la consulta para actualizar tipo de usuario');
    }

    // Vincular los parámetros
    $stmt_tipo->bind_param('i', $persona);

    // Ejecutar la consulta
    if (!$stmt_tipo->execute()) {
        throw new Exception('Error al actualizar el tipo de usuario');
    }

    // Registrar la acción en la bitácora
    $accion = "Registrar Jefe de Manzana";
    $descripcion = "El usuario con ID $persona ha sido asignado como Jefe de Manzana en la manzana con ID $manzana."; // Personaliza la descripción

    // Insertar en la tabla de la bitácora
    $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
    if ($logStmt = $conn->prepare($logQuery)) {
        $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
        $logStmt->execute();
        $logStmt->close();
    }

    // Si ambas operaciones fueron exitosas, confirmar la transacción
    mysqli_commit($conn);

    echo json_encode(['success' => true, 'message' => 'Jefe de manzana registrado correctamente']);

} catch (Exception $e) {
    // Si ocurre un error, revertir la transacción
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Cerrar la sentencia y la conexión
$stmt_jefe->close();
$stmt_tipo->close();
$conn->close();
?>
