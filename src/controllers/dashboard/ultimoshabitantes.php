<?php
// Incluir archivo de conexión
include '../../src/controllers/conexion.php';

// Consultar los últimos habitantes
$sql = "SELECT 
            CONCAT(primer_nombre, ' ', primer_apellido) AS 'Nombre y Apellido', documento_identidad AS 'Cedula', estado AS 'Status' FROM personas LIMIT 10";

$result = $conn->query($sql); // `$conn` debe estar definido en el archivo de conexión

// Generar el HTML para la tabla
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                    <div>
                        <h2 class='font-medium text-gray-800 dark:text-white'>{$row['Nombre y Apellido']}</h2>
                    </div>
                </td>
                <td class='px-12 py-4 text-sm font-medium whitespace-nowrap'>
                    <div class='inline px-3 py-1 text-sm font-normal rounded-full " . ($row['Status'] === 'Activo' ? "text-emerald-500 bg-emerald-100/60" : "text-gray-500 bg-gray-100") . " dark:bg-gray-800'>
                        {$row['Status']}
                    </div>
                </td>
                <td class='px-4 py-4 text-sm whitespace-nowrap'>
                    <div>
                        <h4 class='text-gray-700 dark:text-gray-200'>{$row['Cedula']}</h4>
                    </div>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>No hay habitantes registrados.</td></tr>";
}

$conn->close(); // Cierra la conexión al final
?>
