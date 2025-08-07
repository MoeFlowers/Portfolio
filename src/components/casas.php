<?php
session_start();
include '../../src/controllers/log_action.php';

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    log_action($id_usuario, 'Acceso', 'Entró a la sección Casas');
} else {
    // Redirigir si no está autenticado
    header("Location: ../index.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="../output.css" rel="stylesheet" />
    <title>Casas</title>
    <script src="..//../js//sweetalert2@11.js"></script>
</head>

<body class="font-roboto bg-gray-100 min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php
        include '../components/aside.php';
        ?>
        <div class="container ml-24 lg:ml-64 flex-grow flex flex-col">
            <div class="ml-4 mt-6 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
                <section class="container px-4 mx-auto">
                    <div class="mt-6 md:flex md:items-center md:justify-between">
                        <!--Titulo-->
                        <div>
                            <div class="flex items-center gap-x-3">
                                <?php include '../components/casas/numcasas.php'; ?>
                                <h2 class="text-lg font-medium text-gray-800 dark:text-white">Casas</h2>

                                <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400"><?php echo $total_casas; ?> Casas Registradas</span>
                            </div>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Ultimas Registradas</p>
                        </div>
                        <!--Botones-->
                        <div class="flex items-center mt-4 gap-x-3">
                            <button id="registrarCasaBtn" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                <span>Registrar</span>
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
                            <div
                                class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div
                                    class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                                    <table
                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <!--Columnas-->
                                        <thead class="bg-gray-50 dark:bg-gray-800">

                                            <tr>
                                                <th class="px-4 py-3.5 text-center font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Nro Manzana
                                                </th>
                                                <th class="px-4 py-3.5 text-center font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Nro Casa
                                                </th>
                                                <th class="px-4 py-3.5 text-center font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Nombre Familia
                                                </th>
                                                <th class="px-4 py-3.5 text-center font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Descripción Casa
                                                </th>
                                                <th class="px-12 py-3.5 text-center font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Estado Casa
                                                </th>
                                                <th class="px-12 py-3.5 text-center font-normal text-left text-gray-500 dark:text-gray-400">Acción</th>
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo -->
                                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                            <?php include '../components/casas/getcasas.php'; ?>

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <button class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600" id="registrarCasaBtn">
        Registrar Casa
    </button>

    <script>
        // Mostrar el formulario con SweetAlert2
        document.getElementById('registrarCasaBtn').addEventListener('click', function() {
            // Cargar las manzanas y grupos familiares con PHP
            fetch('../components/casas/getData.php')
                .then(response => response.json()) // Procesar los datos como JSON
                .then(data => {
                    Swal.fire({
                        title: 'Registrar Casa',
                        html: `
                    <label for="nro_manzana">Número de Manzana</label>
                    <select id="nro_manzana" class="swal2-input">
                        ${data.manzanas.map(manzana => `<option value="${manzana.id_manzana}">${manzana.nro_manzana}</option>`).join('')}
                    </select>
                    
                    <label for="numero_casa">Número de Casa</label>
                    <input id="numero_casa" type="text" class="swal2-input" placeholder="Número de la casa" required>

                    <label for="grupo_familiar">Grupo Familiar</label>
                    <select id="grupo_familiar" class="swal2-input">
                        ${data.grupos_familiares.map(grupo => `<option value="${grupo.id_grupo_familiar}">${grupo.nombre_familia}</option>`).join('')}
                    </select>

                    <label for="descripcion_casa">Descripción de la Casa</label>
                    <textarea id="descripcion_casa" class="swal2-textarea" placeholder="Descripción de la casa"></textarea>

                    <label for="estado_casa">Estado de la Casa</label>
                    <select id="estado_casa" class="swal2-input">
                        <option value="Propia">Propia</option>
                        <option value="Alquilada">Alquilada</option>
                    </select>
                `,
                        focusConfirm: false,
                        preConfirm: () => {
                            return {
                                nro_manzana: document.getElementById('nro_manzana').value, // Cambié el ID
                                numero_casa: document.getElementById('numero_casa').value, // Cambié el ID
                                grupo_familiar: document.getElementById('grupo_familiar').value, // Cambié el ID
                                descripcion_casa: document.getElementById('descripcion_casa').value, // Cambié el ID
                                estado_casa: document.getElementById('estado_casa').value // Cambié el ID
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = result.value;

                            // Validación simple para los campos requeridos
                            if (!formData.numero_casa || !formData.descripcion_casa) {
                                Swal.fire('Error', 'Por favor, rellene todos los campos', 'error');
                            } else {
                                // Enviar los datos a PHP
                                fetch('../components/casas/registrar_casa.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json', // Enviar los datos en formato JSON
                                        },
                                        body: JSON.stringify(formData) // Convertir los datos a JSON antes de enviarlos
                                    })
                                    .then(response => response.json()) // Convertir la respuesta a JSON
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire('Registrado', 'La casa ha sido registrada exitosamente', 'success')
                                                .then(() => {
                                                    window.location.reload(); // Recarga la página después de un registro exitoso
                                                });
                                        } else {
                                            Swal.fire('Error', data.message, 'error'); // Mostrar el mensaje de error recibido
                                        }
                                    })
                                    .catch(error => {
                                        Swal.fire('Error', 'Hubo un problema al registrar la casa', 'error'); // Manejo de errores en la solicitud
                                    });
                            }
                        }
                    })
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema al cargar los datos', 'error'); // Manejo de errores en la carga de datos
                });
        });
    </script>


</body>

</html>