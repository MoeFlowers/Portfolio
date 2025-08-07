<?php
include '../../controllers/conexion.php'; // Asegúrate de que la ruta sea correcta

// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no logueado']);
    exit;
}

// Obtener el ID del usuario desde la sesión
$idUsuario = $_SESSION['id_usuario'];

// Obtener los valores del formulario enviados por POST
$idManzana = isset($_POST['id_manzana']) ? $_POST['id_manzana'] : null;
$idCasa = isset($_POST['id_casa']) ? $_POST['id_casa'] : null;
$idGrupoFamiliar = isset($_POST['id_grupo_familiar']) ? $_POST['id_grupo_familiar'] : null;
$idBeneficio = isset($_POST['id_beneficio']) ? $_POST['id_beneficio'] : null;
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : null;

// Validar si todos los campos están presentes
if ($idManzana && $idCasa && $idGrupoFamiliar && $idBeneficio && $observacion) {
    // Verificar si el grupo familiar tiene un jefe de familia
    $query = "SELECT jefe_familia FROM grupos_familiares WHERE id_grupo_familiar = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $idGrupoFamiliar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['jefe_familia'] === 'Si') { // Ajusta según el valor de tu ENUM
                // Si es jefe de familia, realizar la inserción
                $sql = "INSERT INTO historicos (id_manzana, id_casa, id_grupo_familiar, id_beneficio, observacion)
                        VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($sql);

                if ($stmtInsert) {
                    $stmtInsert->bind_param("iiiis", $idManzana, $idCasa, $idGrupoFamiliar, $idBeneficio, $observacion);
                    if ($stmtInsert->execute()) {
                        // Registrar la acción en la bitácora
                        $accion = "Registrar Entrega de Beneficio";
                        $descripcion = "Se registró una entrega de beneficio para el grupo familiar con ID: $idGrupoFamiliar, beneficio ID: $idBeneficio, observación: $observacion";

                        // Insertar en la bitácora
                        $logQuery = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
                        if ($logStmt = $conn->prepare($logQuery)) {
                            $logStmt->bind_param('iss', $idUsuario, $accion, $descripcion);
                            $logStmt->execute();
                            $logStmt->close();
                        }

                        // Respuesta exitosa
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al registrar la entrega.']);
                    }
                    $stmtInsert->close(); // Cerrar solo si está definido
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de inserción.']);
                }
            } else {
                // No es jefe de familia
                echo json_encode(['success' => false, 'message' => 'Solo se pueden registrar entregas para grupos familiares cuyo jefe de familia esté marcado como "sí".']);
            }
        } else {
            // No se encontró el grupo familiar
            echo json_encode(['success' => false, 'message' => 'El grupo familiar no existe.']);
        }
        $stmt->close(); // Cerrar solo si está definido
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de validación.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos para completar el registro.']);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
