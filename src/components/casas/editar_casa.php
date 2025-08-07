<?php
// Incluir configuración o conexión a base de datos si es necesario
include '../../controllers/conexion.php'; // o la conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no logueado']);
    exit();
}

// Obtener el ID del usuario desde la sesión
$idUsuario = $_SESSION['id_usuario'];  // Asegúrate de que el ID del usuario esté guardado en la sesión

// Obtener datos enviados por el método POST
$inputData = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos son válidos
if (isset($inputData['id_casa'])) {
    $idCasa = $inputData['id_casa'];
    $estadoCasa = isset($inputData['estado_casa']) ? $inputData['estado_casa'] : null;
    $descripcionCasa = isset($inputData['descripcion_casa']) ? $inputData['descripcion_casa'] : null;

    // Verificar si se requiere actualización
    $updateFields = [];
    $updateValues = [];
    
    if ($estadoCasa !== null) {
        $updateFields[] = "estado_casa = ?";
        $updateValues[] = $estadoCasa;
    }

    if ($descripcionCasa !== null) {
        $updateFields[] = "descripcion_casa = ?";
        $updateValues[] = $descripcionCasa;
    }

    // Si hay campos para actualizar
    if (!empty($updateFields)) {
        $updateValues[] = $idCasa;  // Agregar el ID al final
        $query = "UPDATE casas SET " . implode(", ", $updateFields) . " WHERE id_casa = ?";

        // Preparar la sentencia SQL
        if ($stmt = $conn->prepare($query)) {
            // Vincular los parámetros
            $stmt->bind_param(str_repeat('s', count($updateValues) - 1) . 'i', ...$updateValues);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Registrar la acción en la bitácora
                $accion = "Editar Casa";
                $descripcion = "Se actualizó la casa con ID: $idCasa";

                // Insertar en la tabla de la bitácora
                $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
                if ($logStmt = $conn->prepare($logQuery)) {
                    $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
                    $logStmt->execute();
                    $logStmt->close();
                }

                // Respuesta exitosa
                echo json_encode(['success' => true]);
            } else {
                // Error al ejecutar la consulta
                echo json_encode(['success' => false, 'error' => 'Error al actualizar los datos']);
            }

            // Cerrar la sentencia
            $stmt->close();
        } else {
            // Error al preparar la consulta
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta']);
        }
    } else {
        echo json_encode(['success' => true]);  // Si no hay cambios, responder éxito sin hacer nada
    }
} else {
    // Datos incompletos
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

// Cerrar la conexión
$conn->close();
?>
