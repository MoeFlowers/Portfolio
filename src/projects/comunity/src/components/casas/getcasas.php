<?php
include '../controladores/conexion.php';

// Consulta para obtener los datos de la vista
$sql = "SELECT id_casa, nro_manzana, nro_casa, nombre_familia, descripcion_casa, estado_casa FROM datos_casas";
$result = $conn->query($sql);



          
            if ($result->num_rows > 0) {
                // Iterar por los resultados y mostrarlos en la tabla
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_manzana']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_casa']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nombre_familia']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['descripcion_casa']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['estado_casa']}</h2>
              </td>";
              echo "<td class='px-4 py-4 text-sm whitespace-nowrap'>
                <div class='flex items-center gap-x-6'>
                    <!-- Botón Editar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none' onclick='editarDetalles({$row['id_casa']})' aria-label='Editar datos'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' />
                        </svg>
                    </button>
                    <!-- Botón Borrar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-blue-600 dark:text-gray-300 hover:text-blue-600 focus:outline-none' onclick='verDetalles({$row['id_casa']})' aria-label='Ver detalles'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
  <path stroke-linecap='round' stroke-linejoin='round' d='m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0' />
</svg>
                    </button>
                    </div>
              </td>";
                }

        
            } else {
                echo "<tr><td colspan='5'>No hay datos disponibles</td></tr>";
            }
            
        

// Cerrar la conexión
$conn->close();
?>



