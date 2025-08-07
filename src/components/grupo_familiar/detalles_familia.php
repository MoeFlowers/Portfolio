<?php
// Conexión a la base de datos
include '../../controllers/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Verificar si se ha recibido el id_familia
if (isset($_POST['id_familia'])) {
    $id_familia = $_POST['id_familia'];

    // Consulta para obtener los miembros de la familia, incluyendo el jefe de familia
    $query = "SELECT id_persona, nombre_y_apellido, parentesco, jefe_familia FROM datos_familias WHERE id_familia = ?";

    // Preparar la consulta
    if ($stmt = $conn->prepare($query)) {
        // Vincular parámetros
        $stmt->bind_param("i", $id_familia);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el resultado
        $resultado = $stmt->get_result();
        
        // Verificar si hay resultados
        if ($resultado->num_rows > 0) {
            // Crear un array para almacenar los miembros
            $miembros = [];
            $jefe_familia = null; // Inicializamos como null

            // Recorrer los resultados para obtener miembros y jefe de familia
            while ($row = $resultado->fetch_assoc()) {
                // Verificar si el miembro es el jefe de familia (comparando con "Si")
                if ($row['jefe_familia'] == "Si") {
                    $jefe_familia = $row['nombre_y_apellido'];
                }

                // Agregar cada miembro al array
                $miembros[] = $row;
            }

            // Verificamos si se ha encontrado el jefe de familia
            if ($jefe_familia === null) {
                // Si no encontramos el jefe de familia, devolvemos un mensaje de error
                echo json_encode(['error' => 'No se encontró el jefe de familia para esta familia.']);
            } else {
                // Devolver la respuesta en formato JSON, incluyendo el jefe de familia y los miembros
                echo json_encode([
                    'jefe_familia' => $jefe_familia, // Agregar el jefe de familia
                    'miembros' => $miembros // Agregar los miembros
                ]);
            }
        } else {
            // Si no hay miembros, retornar un array vacío
            echo json_encode(['error' => 'No se encontraron miembros para esta familia.']);
        }
        
        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Si hubo un error al preparar la consulta
        echo json_encode(['error' => 'Error al preparar la consulta.']);
    }
} else {
    // Si no se recibe el id_familia
    echo json_encode(['error' => 'No se proporcionó el id_familia.']);
}

// Cerrar la conexión
$conn->close();
?>
