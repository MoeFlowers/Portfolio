<?php
// Incluir el archivo de conexión
include '../../src/controllers/conexion.php';



// Consulta a la base de datos
$sql = "SELECT usuario, accion, descripcion, fecha FROM datos_bitacora ORDER BY fecha DESC";
$resultado = $conn->query($sql);

// Generar filas de la tabla
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='px-4 py-4 text-left text-sm font-medium whitespace-nowrap'><h2 class='font-medium text-gray-800 dark:text-white'>{$fila['usuario']}</h2></td>";
        echo "<td class='px-4 py-4 text-left text-sm font-medium whitespace-nowrap'><h2 class='font-medium text-gray-800 dark:text-white'>{$fila['accion']}</h2></td>";
        echo "<td class='px-4 py-4 text-left text-sm font-medium whitespace-nowrap'><h2 class='font-medium text-gray-800 dark:text-white'>{$fila['descripcion']}</h2></td>";
        echo "<td class='px-4 py-4 text-left text-sm font-medium whitespace-nowrap'><h2 class='font-medium text-gray-800 dark:text-white'>{$fila['fecha']}</h2></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' class='px-4 py-4 text-sm  text-center text-gray-500 dark:text-gray-300'>No hay registros en la bitácora.</td></tr>";
}
?>

