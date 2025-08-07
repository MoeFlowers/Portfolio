<?php
// Incluir la conexión a la base de datos
include '../../controllers/conexion.php';

// Iniciar sesión para obtener el ID del usuario
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no logueado");
}

$idUsuario = $_SESSION['id_usuario'];

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados en formato JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si el campo 'nroManzana' está presente en los datos
    if (isset($data['nroManzana']) && !empty($data['nroManzana'])) {
        $numManzana = $data['nroManzana']; // Asegúrate de que el campo sea 'nroManzana'

        // Iniciar una transacción para asegurar que la operación se realice correctamente
        $conn->begin_transaction();

        try {
            // Preparar la consulta para insertar el número de manzana con id_comunidad=1
            $stmt = $conn->prepare("INSERT INTO manzanas (id_comunidad, nro_manzana) VALUES (1, ?)");

            // Vincular el parámetro
            $stmt->bind_param("i", $numManzana); // "i" para entero

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Registrar la acción en la bitácora
                $accion = "Registrar Manzana";
                $descripcion = "Ha registrado la manzana número $numManzana"; // Personaliza la descripción

                // Insertar en la tabla de la bitácora
                $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
                if ($logStmt = $conn->prepare($logQuery)) {
                    $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
                    $logStmt->execute();
                    $logStmt->close();
                }

                // Confirmar la transacción
                $conn->commit();

                // Responder con éxito
                echo json_encode(["success" => true, "message" => "Manzana registrada correctamente."]);
            } else {
                // En caso de error, hacer rollback
                $conn->rollback();
                echo json_encode(["success" => false, "message" => "Error al registrar la manzana: " . $stmt->error]);
            }

            // Cerrar la conexión
            $stmt->close();
        } catch (Exception $e) {
            // En caso de error en el bloque try
            $conn->rollback();
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        } finally {
            $conn->close();
        }
    } else {
        // Si el campo 'nroManzana' no está presente o está vacío
        echo json_encode(["success" => false, "message" => "El campo 'Número de Manzana' es obligatorio."]);
    }
}
?>
