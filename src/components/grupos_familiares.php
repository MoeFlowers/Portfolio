<?php
session_start();
include '../../src/controllers/log_action.php';

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    log_action($id_usuario, 'Acceso', 'Entró a la sección Grupos Familiares');
} else {
    // Redirigir si no está autenticado
    header("Location: ../index.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="../output.css" rel="stylesheet" />
    <title>Grupo Familiares</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.6/dist/sweetalert2.min.js"></script>
</head>

<body class="font-roboto bg-white min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php
        include '../components/aside.php';
        ?>
        <div class="container ml-24 lg:ml-64 flex flex-col">
            <div class="ml-4 mt-6 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
                <section class="container px-4 mx-auto">
                    <div class="flex items-center justify-between border-b pb-2 mb-4">
                        <!--Titulo-->
                        <div>
                            <?php include '../components/grupo_familiar/numgrupos.php'; ?>
                            <div class="flex items-center gap-x-3">
                                <h2 class="text-lg font-medium text-gray-800 dark:text-white">Gestion de Grupos Famliares</h2>
                                <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400"><?php echo $total_grupos; ?> Grupos Familiares Registrados</span>
                            </div>
                        </div>
                        <!--Botones-->
                        <div class="flex items-center mt-4 gap-x-4">
                            <!-- Botón Registrar -->
                            <button id="btn-registrar" class="flex items-center justify-center px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Registrar</span>
                            </button>

                            <!-- Botón Asignar Persona -->
                            <button id="btn-asignar-persona" class="flex items-center justify-center px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Asignar Persona</span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 md:flex md:items-center md:justify-between">
                        <div class="relative flex items-center mt-4 md:mt-0">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </span>
                            <input type="text" placeholder="Search" class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>

                    <div class="flex flex-col mt-6">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <!--Columnas-->
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th class="py-3.5 px-4 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Nombre Familia</th>
                                                <th class="px-4 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Cantidad Miembros</th>
                                                <th class="px-12 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Acción</th>
                                            </tr>
                                        </thead>
                                        <!--Filas-->
                                        <tbody>
                                            <?php include '../components/grupo_familiar/getgrupos.php'; ?>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>

</html>


<script>

document.getElementById('btn-registrar').addEventListener('click', function() {
    // Crear el formulario para registrar el nombre de la familia
    const formulario = `
        <form id="form-registrar-familia" class="space-y-4">
            <div class="flex flex-col">
                <label for="nombre_familia" class="text-lg font-semibold">Nombre de la Familia:</label>
                <input type="text" name="nombre_familia" id="nombre_familia" class="mt-1 p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Registrar</button>
            </div>
        </form>
    `;

    // Mostrar el formulario en un modal utilizando SweetAlert
    Swal.fire({
        title: 'Registrar Familia',
        html: formulario,
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        showConfirmButton: false,
    });

    // Manejar el envío del formulario
    document.getElementById('form-registrar-familia').addEventListener('submit', function (e) {
        e.preventDefault();
        
        const nombreFamilia = document.getElementById('nombre_familia').value;
        
        if (nombreFamilia) {
            // Enviar el nombre de la familia al archivo PHP para insertarlo en la base de datos
            fetch('../components/grupo_familiar/registrar_familia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `nombre_familia=${encodeURIComponent(nombreFamilia)}`
            })
            .then(response => response.text())
            .then(result => {
                Swal.fire('Éxito', result, 'success');
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudo registrar la familia.', 'error');
            });
        }
    });
});





document.getElementById('btn-asignar-persona').addEventListener('click', asignarPersona);

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