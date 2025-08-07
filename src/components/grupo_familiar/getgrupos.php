<?php
// Conexión a la base de datos
include '../controllers/conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta principal
$query = "SELECT DISTINCT nombre_familia, id_familia FROM familias";
$resultado = $conn->query($query);

if ($resultado->num_rows > 0) {
    // Genera dinámicamente las filas de la tabla
    while ($row = $resultado->fetch_assoc()) {
        $id_familia = $row['id_familia'];

        // Consulta para obtener la cantidad de miembros
        $query_miembros = "SELECT COUNT(*) AS cantidad_miembros FROM grupos_familiares WHERE id_familia = '$id_familia'";
        $resultado_miembros = $conn->query($query_miembros);
        $cantidad_miembros = $resultado_miembros->fetch_assoc()['cantidad_miembros'];

        echo "<tr>";
        
        echo "<td class='px-4 py-4 text-left text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$row['nombre_familia']}</h2>
              </td>";
        
        echo "<td class='px-4 py-4 text-center text-sm font-medium whitespace-nowrap'>
                <h2 class='font-medium text-gray-800 dark:text-white'>{$cantidad_miembros}</h2>
              </td>";

              echo "<td class='px-4 py-4 text-center text-sm whitespace-nowrap'>
                
              <div class='flex items-start justify-start gap-x-6'>
  <button class='text-gray-500 transition-colors duration-200 dark:hover:text-blue-600 dark:text-gray-300 hover:text-blue-600 focus:outline-none' onclick='verDetalles({$id_familia})'>
      <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'>
          <path stroke-linecap='round' stroke-linejoin='round' d='M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z' />
          <path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
      </svg>
  </button>

  <button class='text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none' onclick='editarFamilia({$id_familia})'>
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


<script src="..//../js//sweetalert2@11.js"></script>


<script>

// Función para mostrar los miembros de la familia
// Función para mostrar los miembros de la familia
function verDetalles(idFamilia) {
    // Realizamos la solicitud usando fetch
    fetch('../components/grupo_familiar/detalles_familia.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',  // Asegura que el contenido sea tratado como formulario
        },
        body: new URLSearchParams({
            'id_familia': idFamilia  // Pasamos el id de la familia
        })
    })
    .then(response => response.json())  // Convertimos la respuesta a JSON
    .then(data => {
        // Comprobamos si se obtuvo la información correctamente
        if (data.error) {
            Swal.fire({
                title: 'Error',
                text: data.error,
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
            return;
        }

        // Crear el contenido del modal
        let contenido = `<h3><strong>Jefe de Familia:</strong> ${data.jefe_familia}</h3>`;
        contenido += '<ul>';

        // Listar los miembros de la familia
        data.miembros.forEach(function(miembro) {
            contenido += `<li><strong>${miembro.nombre_y_apellido}</strong> - ${miembro.parentesco}`;
            if (miembro.jefe_familia == 1) {
                contenido += ' (Jefe de familia)';
            }
            contenido += '</li>';
        });

        contenido += '</ul>';

        // Usamos SweetAlert2 para mostrar el modal
        Swal.fire({
            title: 'Miembros de la familia',
            html: contenido,
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al obtener los datos.',
            icon: 'error',
            confirmButtonText: 'Cerrar'
        });
    });
}


function editarFamilia(id_familia) {
    // Obtener los detalles de la familia y los miembros
    fetch('../components/grupo_familiar/detalles_familia.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'id_familia': id_familia  // Pasamos el id de la familia
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            Swal.fire({
                title: 'Error',
                text: data.error,
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
            return;
        }

        // Crear la lista de miembros con un botón de borrar
        let formulario = `
            <h4 class="text-xl font-bold mb-4">Miembros de la familia</h4>
            <ul class="space-y-2">`;

        // Mostrar miembros con el botón de borrar
        data.miembros.forEach(function(miembro) {
            formulario += `
                <li class="flex justify-between items-center">
                    <div>
                        <strong>${miembro.nombre_y_apellido}</strong> - ${miembro.parentesco}
                        ${miembro.jefe_familia === 'Sí' ? ' (Jefe de familia)' : ''}
                    </div>
                    <button class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition transform hover:scale-105" onclick="borrarMiembro(${miembro.id_persona}, ${id_familia})">
                        Borrar
                    </button>
                </li>
            `;
        });

        formulario += '</ul>';

        // Mostrar la lista de miembros en un modal usando SweetAlert2
        Swal.fire({
            title: 'Editar Familia',
            html: formulario,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Cerrar',
            width: 600, // Asegurarse de que el modal tenga suficiente espacio
            customClass: {
                content: 'overflow-auto',
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al obtener los datos.',
            icon: 'error',
            confirmButtonText: 'Cerrar'
        });
    });
}

// Función para borrar un miembro
function borrarMiembro(idPersona, idFamilia) {
    // Confirmar si el usuario está seguro de borrar el miembro
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500',
            cancelButton: 'bg-gray-300 hover:bg-gray-400'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar solicitud de borrado
            fetch('../components/grupo_familiar/borrar_miembro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id_persona': idPersona,
                    'id_familia': idFamilia
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El miembro ha sido borrado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'Cerrar'
                    });
                    // No es necesario volver a llamar a editarFamilia
                    // Actualiza la UI aquí o simplemente actualiza la lista de miembros manualmente
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el miembro.',
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al eliminar el miembro.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
            });
        }
    });
}





function asignarPersona() {
    // Realizar una solicitud AJAX para cargar las opciones dinámicas
    fetch('../components/grupo_familiar/obtener_datos.php')
        .then(response => response.json())
        .then(data => {
            const { personas, familias } = data;

            // Crear las opciones de las listas desplegables
            const personasOptions = personas.map(
                persona => `<option value="${persona.id_persona}">${persona.nombre_completo}</option>`
            ).join('');
            
            const familiasOptions = familias.map(
                familia => `<option value="${familia.id_familia}">${familia.nombre_familia}</option>`
            ).join('');

            const parentescoOptions = `
                <option value="Madre">Madre</option>
                <option value="Padre">Padre</option>
                <option value="Hijo(a)">Hijo(a)</option>
                <option value="Hermano(a)">Hermano(a)</option>
                <option value="Suegro(a)">Suegro(a)</option>
                <option value="Yerno">Yerno</option>
                <option value="Nuera">Nuera</option>
            `;

            const jefeFamiliaOptions = `
                <option value="Si">Si</option>
                <option value="No">No</option>
            `;

            // Construir el formulario HTML con Tailwind CSS
            const formulario = `
                <form id="asignar-form" class="space-y-4">
                    <div class="flex flex-col">
                        <label for="persona" class="text-lg font-semibold">Persona:</label>
                        <select name="id_persona" id="persona" class="mt-1 p-2 border border-gray-300 rounded-md" required>
                            ${personasOptions}
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label for="familia" class="text-lg font-semibold">Familia:</label>
                        <select name="id_familia" id="familia" class="mt-1 p-2 border border-gray-300 rounded-md" required>
                            ${familiasOptions}
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label for="parentesco" class="text-lg font-semibold">Parentesco:</label>
                        <select name="parentesco" id="parentesco" class="mt-1 p-2 border border-gray-300 rounded-md" required>
                            ${parentescoOptions}
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label for="jefe_familia" class="text-lg font-semibold">Jefe de Familia:</label>
                        <select name="jefe_familia" id="jefe_familia" class="mt-1 p-2 border border-gray-300 rounded-md" required>
                            ${jefeFamiliaOptions}
                        </select>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Asignar</button>
                    </div>
                </form>
            `;

            // Mostrar el modal
            Swal.fire({
                title: 'Asignar Persona a Familia',
                html: formulario,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                showConfirmButton: false,
            });

            // Agregar manejador de evento al formulario
            document.getElementById('asignar-form').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                // Enviar los datos al servidor
                fetch('../components/grupo_familiar/asignar_persona.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(result => {
                        Swal.fire('Éxito', result, 'success');
                    })
                    .catch(error => {
                        Swal.fire('Error', 'No se pudo asignar la persona.', 'error');
                    });
            });
        })
        .catch(error => {
            Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
        });
}




</script>