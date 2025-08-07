<?php
// Conexión a la base de datos
include '../controllers/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta
$query = "SELECT nombre_y_apellido, documento_identidad, telefono, nro_manzana, id_usuario, status FROM datos_jefes_manzanas ";
$resultado = $conn->query($query);

if ($resultado->num_rows > 0) {
    // Genera dinámicamente las filas de la tabla
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nombre_y_apellido']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['documento_identidad']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['telefono']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
              <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nro_manzana']}</h2>
             </td>";
        echo "<td class='px-12 py-4 text-center text-sm font-medium whitespace-nowrap'>
                    <div class='inline px-3 py-1 text-sm font-normal rounded-full " . ($row['status'] === 'Activo' ? "text-emerald-500 bg-emerald-100/60" : "text-gray-500 bg-gray-100") . " dark:bg-gray-800'>
                        {$row['status']}
                    </div>
                </td>";
        echo "<td class='px-4 py-4  text-sm whitespace-nowrap'>
                <div class='flex items-center gap-x-6'>
                    <!-- Botón Editar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none' onclick='editarDetalles({$row['id_usuario']})' aria-label='Editar datos'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' />
                        </svg>
                    </button>
                    <!-- Botón Borrar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-blue-600 dark:text-gray-300 hover:text-blue-600 focus:outline-none' onclick='eliminarjefe({$row['id_usuario']})' aria-label='Ver detalles'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
  <path stroke-linecap='round' stroke-linejoin='round' d='m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0' />
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
    function editarDetalles(id_usuario) {
        // Mostrar una alerta para cambiar el estado
        Swal.fire({
            title: 'Cambiar estado',
            input: 'select',
            inputOptions: {
                'Activo': 'Activo',
                'Inactivo': 'Inactivo'
            },
            inputPlaceholder: 'Seleccione un nuevo estado',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar solicitud para actualizar el estado
                const nuevoEstado = result.value;

                fetch('../components/jefe_manzana/actualizar_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_usuario,
                            status: nuevoEstado
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Éxito', 'Estado actualizado correctamente', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'No se pudo actualizar el estado', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Ocurrió un error al actualizar el estado', 'error');
                        console.error('Error:', error);
                    });
            }
        });
    }
</script>