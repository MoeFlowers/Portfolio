<?php
include '../../controladores/conexion.php'; // Asegúrate de que la ruta sea correcta

// Obtener los valores del formulario enviados por POST
$idManzana = isset($_POST['id_manzana']) ? $_POST['id_manzana'] : null;
$idCasa = isset($_POST['id_casa']) ? $_POST['id_casa'] : null;
$idGrupoFamiliar = isset($_POST['id_grupo_familiar']) ? $_POST['id_grupo_familiar'] : null;
$idBeneficio = isset($_POST['id_beneficio']) ? $_POST['id_beneficio'] : null;
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : null;

// Validar si todos los campos están presentes
if ($idManzana && $idCasa && $idGrupoFamiliar && $idBeneficio && $observacion) {
    // Insertar en la tabla historicos (ajusta el nombre de la tabla y las columnas según tu base de datos)
    $sql = "INSERT INTO historicos (id_manzana, id_casa, id_grupo_familiar, id_beneficio, observacion)
            VALUES (?, ?, ?, ?, ?)";

    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar los parámetros
        $stmt->bind_param("iiiis", $idManzana, $idCasa, $idGrupoFamiliar, $idBeneficio, $observacion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la inserción es exitosa, enviar una respuesta de éxito
            echo json_encode(['success' => true]);
        } else {
            // Si hubo un error en la inserción
            echo json_encode(['success' => false, 'message' => 'Error al registrar la entrega.']);
        }

        // Cerrar la consulta
        $stmt->close();
    } else {
        // Si no se pudo preparar la consulta
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    }
} else {
    // Si falta algún campo
    echo json_encode(['success' => false, 'message' => 'Faltan datos para completar el registro.']);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
