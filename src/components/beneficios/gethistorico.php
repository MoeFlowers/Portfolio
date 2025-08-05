<?php
// Conexión a la base de datos
include '../controladores/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta
$query = "SELECT * FROM datos_historicos";
$resultado = $conn->query($query);

if ($resultado->num_rows > 0) {
    // Genera dinámicamente las filas de la tabla
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nombre_beneficio']}</h2>
              </td>";
               echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='text-sm font-normal text-gray-600 dark:text-gray-400'>{$row['nombre_familia']}</h2>
                </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_manzana']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_casa']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['fecha_entregado']}</h2>
              </td>";
              echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['observacion']}</h2>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' class='px-4 py-4 text-sm text-center'>No hay datos disponibles</td></tr>";
}

// Cierra la conexión
$conn->close();
?>




