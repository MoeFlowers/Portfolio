<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="../output.css" rel="stylesheet" />
    <title>Habitantes</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-roboto bg-gray-100 min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php
        include '../components/aside.php';
        ?>
        <div class="ml-64 flex-grow flex flex-col">
            <div class="ml-4 mt-6 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
                <section class="container px-4 mx-auto">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <!--Titulo-->
                        <div>
                            <div class="flex items-center gap-x-3">
                            <?php include '../components/habitantes/numpersonas.php'; ?>
                                <h2 class="text-lg font-medium text-gray-800 dark:text-white">Habitantes</h2>
                                <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400"><?php echo $total_personas; ?> Habitantes</span>
                            </div>
                        </div>
                        <!--Botones-->
                        <div class="flex items-center mt-4 gap-x-3">
                            <button id="registerButton" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
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
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Encabezados -->
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Nombre y Apellido</th>
                    <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Cédula</th>
                    <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Teléfono</th>
                    <th class="px-12 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-12 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Acción</th>
                </tr>
            </thead>
            <!-- Cuerpo -->
            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                <?php include '../components/habitantes/gethabitantes.php'; ?>
               
            </tbody>
        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-6">
                        <a
                            href="#"
                            class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 rtl:-scale-x-100">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                            </svg>

                            <span> previous </span>
                        </a>

                        <div class="items-center hidden md:flex gap-x-3">
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60">1</a>
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">2</a>
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">3</a>
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">...</a>
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">12</a>
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">13</a>
                            <a
                                href="#"
                                class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">14</a>
                        </div>

                        <a
                            href="#"
                            class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                            <span> Next </span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 rtl:-scale-x-100">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        // Función para mostrar SweetAlert2 con el formulario de registro
        document.getElementById('registerButton').addEventListener('click', function() {
    Swal.fire({
        title: 'Registrar Habitante',
        html: `
            <form id="registerForm">
                <div class="mb-4">
                    <label for="usuario" class="block text-gray-700">Usuario:</label>
                    <input type="text" id="usuario" class="swal2-input" placeholder="Usuario" required>
                </div>
                <div class="mb-4">
                    <label for="contraseña" class="block text-gray-700">Contraseña:</label>
                    <input type="password" id="contraseña" class="swal2-input" placeholder="Contraseña" required>
                </div>
                <div class="mb-4">
                    <label for="primer_nombre" class="block text-gray-700">Primer Nombre:</label>
                    <input type="text" id="primer_nombre" class="swal2-input" placeholder="Primer Nombre" required>
                </div>
                <div class="mb-4">
                    <label for="segundo_nombre" class="block text-gray-700">Segundo Nombre:</label>
                    <input type="text" id="segundo_nombre" class="swal2-input" placeholder="Segundo Nombre">
                </div>
                <div class="mb-4">
                    <label for="primer_apellido" class="block text-gray-700">Primer Apellido:</label>
                    <input type="text" id="primer_apellido" class="swal2-input" placeholder="Primer Apellido" required>
                </div>
                <div class="mb-4">
                    <label for="segundo_apellido" class="block text-gray-700">Segundo Apellido:</label>
                    <input type="text" id="segundo_apellido" class="swal2-input" placeholder="Segundo Apellido">
                </div>
                <div class="mb-4">
                    <label for="documento_identidad" class="block text-gray-700">Documento de Identidad:</label>
                    <input type="text" id="documento_identidad" class="swal2-input" placeholder="Documento de Identidad" required>
                </div>
                <div class="mb-4">
                    <label for="codigo_carnet_patria" class="block text-gray-700">Código Carnet de la Patria:</label>
                    <input type="text" id="codigo_carnet_patria" class="swal2-input" placeholder="Código Carnet de la Patria">
                </div>
                <div class="mb-4">
                    <label for="serial_carnet_patria" class="block text-gray-700">Serial Carnet de la Patria:</label>
                    <input type="text" id="serial_carnet_patria" class="swal2-input" placeholder="Serial Carnet de la Patria">
                </div>
                <div class="mb-4">
                    <label for="genero" class="block text-gray-700">Género:</label>
                    <select id="genero" class="swal2-input">
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="fecha_nacimiento" class="block text-gray-700">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" class="swal2-input" required>
                </div>
                <div class="mb-4">
                    <label for="cantidad_hijos" class="block text-gray-700">Cantidad de Hijos:</label>
                    <input type="number" id="cantidad_hijos" class="swal2-input" placeholder="Cantidad de Hijos">
                </div>
                <div class="mb-4">
                    <label for="telefono" class="block text-gray-700">Teléfono:</label>
                    <input type="text" id="telefono" class="swal2-input" placeholder="Teléfono" required>
                </div>
                <div class="mb-4">
                    <label for="correo" class="block text-gray-700">Correo:</label>
                    <input type="email" id="correo" class="swal2-input" placeholder="Correo">
                </div>
            </form>
        `,
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Registrar',
        preConfirm: () => {
            const usuario = document.getElementById('usuario').value;
            const contraseña = document.getElementById('contraseña').value;
            const primer_nombre = document.getElementById('primer_nombre').value;
            const segundo_nombre = document.getElementById('segundo_nombre').value;
            const primer_apellido = document.getElementById('primer_apellido').value;
            const segundo_apellido = document.getElementById('segundo_apellido').value;
            const documento_identidad = document.getElementById('documento_identidad').value;
            const codigo_carnet_patria = document.getElementById('codigo_carnet_patria').value;
            const serial_carnet_patria = document.getElementById('serial_carnet_patria').value;
            const genero = document.getElementById('genero').value;
            const fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
            const cantidad_hijos = document.getElementById('cantidad_hijos').value;
            const telefono = document.getElementById('telefono').value;
            const correo = document.getElementById('correo').value;

            if (!usuario || !contraseña || !primer_nombre || !primer_apellido || !documento_identidad || !telefono) {
                Swal.showValidationMessage('Por favor complete todos los campos obligatorios');
                return false;
            }

            // Enviar los datos al servidor con fetch
            fetch('../components/habitantes/crear_persona.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    usuario,
                    contraseña,
                    primer_nombre,
                    segundo_nombre,
                    primer_apellido,
                    segundo_apellido,
                    documento_identidad,
                    codigo_carnet_patria,
                    serial_carnet_patria,
                    genero,
                    fecha_nacimiento,
                    cantidad_hijos,
                    telefono,
                    correo
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('¡Registro Exitoso!', 'El habitante ha sido registrado correctamente.', 'success')
                    .then(() => {
                        // Recargar la página después de la alerta de éxito
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            
        }
    });
});





    </script>
</body>

</html>