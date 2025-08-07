<?php
session_start();
include '../../src/controllers/log_action.php';

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    log_action($id_usuario, 'Acceso', 'Entró a la sección Jefe de Manzana');
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
    <title>Jefe de Manzana</title>
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
                        <?php include '../components/jefe_manzana/numjefes.php'; ?>
                            <div class="flex items-center gap-x-3">
                                
                                <h2 class="text-lg font-medium text-gray-800 dark:text-white">Jefes de Manzanas</h2>

                                <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400"><?php echo $total_jefes; ?> Jefes Registrados</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Ultimos Registrados</p>
                        </div>
                        <!--Botones-->
                        <div class="flex items-center mt-4 gap-x-3">
                            <button onclick="registrarjefe()" class="flex items-center justify-center w-full  px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
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
                                                <th class="py-3.5 px-4 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Nombre y Apellido</th>
                                                <th class="px-4 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Cédula</th>
                                                <th class="px-12 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Numero de Telefono</th>
                                                <th class="px-4 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Numero de Manzana</th>
                                                <th class="px-12 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Status</th>
                                                <th class="px-12 py-3.5 text-center  font-normal text-left text-gray-500 dark:text-gray-400">Accion</th>
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo -->
                                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                            <?php include '../components/jefe_manzana/getjefes.php'; ?>
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
    // Función para abrir el modal con SweetAlert2
    function registrarjefe() {
        // Consultar los habitantes y las manzanas desde el servidor
        fetch('../components/jefe_manzana/obtener_datos.php')
            .then(response => response.json())
            .then(data => {
                // Extraer personas y manzanas del JSON
                const personas = data.personas;
                const manzanas = data.manzanas;

                // Construir las opciones de las listas desplegables
                let personasOptions = "<option value=''>Seleccione una persona</option>";
                personas.forEach(persona => {
                    personasOptions += `<option value='${persona.id_usuario}'>${persona.nombre_y_apellido}</option>`;
                });

                let manzanasOptions = "<option value=''>Seleccione una manzana</option>";
                manzanas.forEach(manzana => {
                    manzanasOptions += `<option value='${manzana.id_manzana}'>${manzana.nro_manzana}</option>`;
                });

                // Mostrar el formulario usando SweetAlert2
                Swal.fire({
                    title: 'Registrar Jefe de Manzana',
                    html: ` 
                    <form id="registroForm">
                        <div class="swal-input-container">
                            <label for="persona">Seleccionar Persona</label>
                            <select id="persona" name="persona" class="swal2-input" required>
                                ${personasOptions}
                            </select>
                        </div>
                        <div class="swal-input-container">
                            <label for="manzana">Seleccionar Manzana</label>
                            <select id="manzana" name="manzana" class="swal2-input" required>
                                ${manzanasOptions}
                            </select>
                        </div>
                    </form>
                `,
                    showCancelButton: true,
                    confirmButtonText: 'Registrar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: () => {
                        const persona = document.getElementById('persona').value;
                        const manzana = document.getElementById('manzana').value;

                        // Validar los campos antes de enviar
                        if (!persona || !manzana) {
                            Swal.showValidationMessage('Por favor, complete todos los campos.');
                            return false;
                        }

                        // Devolver los datos del formulario
                        return {
                            persona,
                            manzana
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const {
                            persona,
                            manzana
                        } = result.value;

                        // Realizar el envío de los datos usando fetch (AJAX)
                        fetch('../components/jefe_manzana/registro_jefe.php', {
                                method: 'POST',
                                body: JSON.stringify({
                                    persona,
                                    manzana
                                }),
                                headers: {
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('¡Éxito!', 'El jefe de manzana fue registrado correctamente.', 'success');
                                } else {
                                    Swal.fire('Error', 'Hubo un problema al registrar el jefe de manzana.', 'error');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Error', 'Hubo un error al enviar los datos.', 'error');
                            });
                    }
                });
            })
            .catch(error => {
                Swal.fire('Error', 'Hubo un problema al obtener los datos.', 'error');
            });
    }


    function eliminarjefe(id_usuario) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás recuperar este registro!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('../components/jefe_manzana/eliminarjefe.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_usuario
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Eliminado!', 'El jefe de manzana ha sido eliminado.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Ocurrió un error al eliminar el usuario', 'error');
                        console.error('Error:', error);
                    });
            }
        });
    }
</script>