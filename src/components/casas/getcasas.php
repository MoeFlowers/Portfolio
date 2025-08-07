<?php
include '../controllers/conexion.php';

// Consulta para obtener los datos de la vista
$sql = "SELECT id_casa, nro_manzana, nro_casa, nombre_familia, descripcion_casa, estado_casa FROM datos_casas";
$result = $conn->query($sql);




if ($result->num_rows > 0) {
    // Iterar por los resultados y mostrarlos en la tabla
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_manzana']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_casa']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nombre_familia']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['descripcion_casa']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['estado_casa']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm whitespace-nowrap'>
                <div class='flex items-center justify-center gap-x-6'>
                    <!-- Botón Editar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none' onclick='editarEstadoCasa({$row['id_casa']})' aria-label='Editar datos'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' />
                        </svg>
                    </button>
                    <!-- Botón Borrar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-blue-600 dark:text-gray-300 hover:text-blue-600 focus:outline-none' onclick='eliminarCasa({$row['id_casa']})' aria-label='Ver detalles'>
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

<script src="..//../js//sweetalert2@11.js"></script>


<script>
    function editarEstadoCasa(id_casa, estadoActual, descripcionActual) {
        // Mostrar un formulario para editar
        Swal.fire({
            title: 'Editar Casa',
            html: `
                <select id="estado_casa" class="swal2-input">
                    <option value="">Seleccione un estado</option>
                    <option value="Propia">Propia</option>
                    <option value="Alquilada">Alquilada</option>
                </select>
                <textarea id="descripcion_casa" class="swal2-input" placeholder="Descripción de la casa">${descripcionActual || ''}</textarea>
            `,
            focusConfirm: false,
            preConfirm: () => {
                const estado_casa = document.getElementById('estado_casa').value;
                const descripcion_casa = document.getElementById('descripcion_casa').value;

                // Verificar si el estado o la descripción han cambiado
                const estadoCambio = estado_casa && estado_casa !== estadoActual;
                const descripcionCambio = descripcion_casa && descripcion_casa !== descripcionActual;

                // Retornar los valores solo si han cambiado
                return {
                    id_casa,
                    estado_casa: estadoCambio ? estado_casa : null,
                    descripcion_casa: descripcionCambio ? descripcion_casa : null
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar los datos actualizados al servidor
                fetch('../components/casas/editar_casa.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: 'Los datos han sido actualizados',
                                icon: 'success',
                                confirmButtonText: 'Cerrar'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudieron actualizar los datos',
                                icon: 'error',
                                confirmButtonText: 'Cerrar'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al actualizar los datos',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                        console.error('Error al actualizar:', error);
                    });
            }
        });
    }


    // Función para eliminar una casa (sin cambios)
    function eliminarCasa(idCasa) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará permanentemente la casa.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../components/casas/eliminar_casa.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_casa: idCasa
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Eliminado',
                                text: 'La casa ha sido eliminada.',
                                icon: 'success',
                                confirmButtonText: 'Cerrar'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo eliminar la casa.',
                                icon: 'error',
                                confirmButtonText: 'Cerrar'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al eliminar la casa.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                        console.error('Error al eliminar:', error);
                    });
            }
        });
    }
</script>