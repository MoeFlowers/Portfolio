<?php
// Conexión a la base de datos
include '../controllers/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta
$query = "SELECT CONCAT(primer_nombre, ' ' ,segundo_nombre) AS nombres, CONCAT(primer_apellido, ' ' ,segundo_apellido) AS apellidos, documento_identidad AS cedula, telefono, estado AS status, id_persona FROM personas"; // Cambié la consulta para incluir el campo 'id'
$resultado = $conn->query($query);

if ($resultado->num_rows > 0) {
    // Genera dinámicamente las filas de la tabla
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <div>
                    <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nombres']}</h2>
                    <p class='text-sm font-normal text-gray-600 dark:text-gray-400'>{$row['apellidos']}</p>
                </div>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['cedula']}</h2>
              </td>";
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['telefono']}</h2>
              </td>";
        echo "<td class='px-12 py-4 text-center text-sm font-medium whitespace-nowrap'>
                    <div class='inline px-3 py-1 text-sm font-normal rounded-full " . ($row['status'] === 'Activo' ? "text-emerald-500 bg-emerald-100/60" : "text-gray-500 bg-gray-100") . " dark:bg-gray-800'>
                        {$row['status']}
                    </div>
                </td>";
        echo "<td class='px-4 py-4 text-right text-sm whitespace-nowrap'>
                <div class='flex items-center justify-center gap-x-6'>
                    <!-- Botón Ver -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-blue-600 dark:text-gray-300 hover:text-blue-600 focus:outline-none' onclick='verDetalles({$row['id_persona']})' aria-label='Ver detalles'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z' />
                            <path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
                        </svg>
                    </button>
                    <!-- Botón Editar -->
                    <button class='text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none' onclick='editarDetalles({$row['id_persona']})' aria-label='Editar datos'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' />
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
    // Función para mostrar los detalles
    function verDetalles(idPersona) {
    // Realizar una petición para obtener los detalles desde el servidor
    fetch(`../components/habitantes/detalles_persona.php?id_persona=${idPersona}`)
        .then(response => {
            // Verificar si la respuesta es válida
            if (!response.ok) {
                throw new Error('No se pudo obtener la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error); // Si hay un error en los datos JSON, lo lanzamos
            }

            // Usar SweetAlert2 para mostrar los detalles
            Swal.fire({
                title: 'Detalles del Habitante',
                html: `
                    <p><strong>Nombre:</strong> ${data.primer_nombre} ${data.segundo_nombre}</p>
                    <p><strong>Apellido:</strong> ${data.primer_apellido} ${data.segundo_apellido}</p>
                    <p><strong>Cédula:</strong> ${data.documento_identidad}</p>
                    <p><strong>Serial Carnet Patria:</strong> ${data.serial_carnet_patria}</p>
                    <p><strong>Codigo Carnet Patria:</strong> ${data.codigo_carnet_patria}</p>
                    <p><strong>Teléfono:</strong> ${data.telefono}</p>
                    <p><strong>Correo:</strong> ${data.correo}</p>
                    <p><strong>Fecha de Nacimiento:</strong> ${data.fecha_nacimiento}</p>
                    <p><strong>Genero:</strong> ${data.genero}</p>
                    <p><strong>Cantidad de Hijos:</strong> ${data.cantidad_hijos}</p>
                    <p><strong>Fecha de Registro:</strong> ${data.fecha_registro}</p>
                    
                `,
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: `No se pudieron obtener los detalles: ${error.message}`,
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
            console.error('Error al obtener los detalles:', error);
        });
}




function editarDetalles(id_persona) {
    // Mostrar un formulario para editar
    Swal.fire({
        title: 'Editar Datos',
        html: `
            <input type="text" id="primer_nombre" class="swal2-input" placeholder="Primer Nombre" />
            <input type="text" id="segundo_nombre" class="swal2-input" placeholder="Segundo Nombre" />
            <input type="text" id="primer_apellido" class="swal2-input" placeholder="Primer Apellido" />
            <input type="text" id="segundo_apellido" class="swal2-input" placeholder="Segundo Apellido" />
            <input type="text" id="documento_identidad" class="swal2-input" placeholder="Documento de Identidad" />
            <input type="text" id="codigo_carnet_patria" class="swal2-input" placeholder="Código Carnet Patria" />
            <input type="text" id="serial_carnet_patria" class="swal2-input" placeholder="Serial Carnet Patria" />
            <input type="text" id="genero" class="swal2-input" placeholder="Género" />
            <input type="date" id="fecha_nacimiento" class="swal2-input" placeholder="Fecha de Nacimiento" />
            <input type="number" id="cantidad_hijos" class="swal2-input" placeholder="Cantidad de Hijos" />
            <input type="text" id="telefono" class="swal2-input" placeholder="Teléfono" />
            <input type="email" id="correo" class="swal2-input" placeholder="Correo" />
        `,
        focusConfirm: false,
        preConfirm: () => {
            // Obtener los valores de los campos
            const primer_nombre = document.getElementById('primer_nombre').value || null;
            const segundo_nombre = document.getElementById('segundo_nombre').value || null;
            const primer_apellido = document.getElementById('primer_apellido').value || null;
            const segundo_apellido = document.getElementById('segundo_apellido').value || null;
            const documento_identidad = document.getElementById('documento_identidad').value || null;
            const codigo_carnet_patria = document.getElementById('codigo_carnet_patria').value || null;
            const serial_carnet_patria = document.getElementById('serial_carnet_patria').value || null;
            const genero = document.getElementById('genero').value || null;
            const fecha_nacimiento = document.getElementById('fecha_nacimiento').value || null;
            const cantidad_hijos = document.getElementById('cantidad_hijos').value || null;
            const telefono = document.getElementById('telefono').value || null;
            const correo = document.getElementById('correo').value || null;

            // Retornar solo los valores no nulos para enviar
            return { id_persona, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, documento_identidad, 
                     codigo_carnet_patria, serial_carnet_patria, genero, fecha_nacimiento, cantidad_hijos, telefono, correo };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar los datos actualizados al servidor
            fetch('../components/habitantes/editar_persona.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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


</script>

