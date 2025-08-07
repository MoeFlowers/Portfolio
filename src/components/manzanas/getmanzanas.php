<?php
// Conexión a la base de datos
include '../controllers/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta
$query = "SELECT * FROM datos_manzanas"; // Cambié la consulta para incluir el campo 'id'
$resultado = $conn->query($query);

if ($resultado->num_rows > 0) {
    // Genera dinámicamente las filas de la tabla
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";

        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_manzana']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['total_casas']}</h2>
              </td>";
        echo "<td class='px-4 py-4  text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['total_grupos_familiares']}</h2>
              </td>";
        echo "<td class='px-4 py-4  text-right text-sm whitespace-nowrap'>
                <div class='flex items-center justify-center gap-x-6'>
                    <!-- Botón Ver -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-blue-600 dark:text-gray-300 hover:text-blue-600 focus:outline-none' 
                    onclick='verDetalles({$row["id_manzana"]})' aria-label='Ver detalles'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z' />
                            <path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
                        </svg>
                    </button>
                    
                </div>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' class='px-4 py-4 text-sm text-center'>No hay datos disponibles</td></tr>";
}

// Cierra la conexión
$conn->close();
?>

<!-- Incluir SweetAlert2 -->
<script src="..//../js//sweetalert2@11.js"></script>


<script>
    function verDetalles(id_manzana) {
        fetch(`./manzanas/detalles_casas.php?id_manzana=${id_manzana}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                } else {
                    let detalles = `<table class="table-auto w-full">
                                  <thead>
                                    <tr>
                                      <th class="px-4 py-2">Manzana</th>
                                      <th class="px-4 py-2">Casa</th>
                                      <th class="px-4 py-2">Descripción</th>
                                      <th class="px-4 py-2">Estado</th>
                                      <th class="px-4 py-2">Familia</th>
                                      <th class="px-4 py-2">Jefe de Familia</th>
                                    </tr>
                                  </thead>
                                  <tbody>`;

                    data.forEach(item => {
                        detalles += `<tr>
                                   <td class="border px-4 py-2">${item.nro_manzana}</td>
                                   <td class="border px-4 py-2">${item.nro_casa}</td>
                                   <td class="border px-4 py-2">${item.descripcion_casa}</td>
                                   <td class="border px-4 py-2">${item.estado_casa}</td>
                                   <td class="border px-4 py-2">${item.nombre_familia}</td>
                                   <td class="border px-4 py-2">${item.nombre}</td>
                                 </tr>`;
                    });

                    detalles += `</tbody></table>`;

                    Swal.fire({
                        title: `Detalles de la Manzana ${data[0]?.nro_manzana || ''}`,
                        html: detalles,
                        width: '80%',
                        confirmButtonText: 'Cerrar'
                    });
                }
            })
            .catch(error => Swal.fire('Error', 'No se pudo cargar la información', 'error'));
    }
</script>