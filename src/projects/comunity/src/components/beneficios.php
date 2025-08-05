<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="../output.css" rel="stylesheet" />
    <title>Beneficios</title>
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
                            <?php include '../components/beneficios/numbeneficios.php'; ?>
                                <h2 class="text-lg font-medium text-gray-800 dark:text-white">Historico de Beneficios</h2>

                                <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400"><?php echo $total_beneficios; ?> Beneficios Registrados</span>
                            </div>
                        </div>
                        <!--Botones-->
                        <div class="flex items-center mt-4 gap-x-3">
                            <button id="registrarbeneficio" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Registrar Beneficio</span>
                            </button>
                            <button id="mostrarbeneficios" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>Mostrar Beneficios Registrados</span>
</button>

                            <button id="registrarentrega" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Registrar Entrega</span>
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
                                        <!--Columnas-->
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                    <th class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Nombre de Beneficio</th>
                    <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Familia</th>
                    <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Numero de Manzana</th>
                    <th class="px-12 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Numero de Casa</th>
                    <th class="px-12 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Fecha Entregado</th>
                    <th class="px-12 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Observacion</th>
                    </thead>
                                        <!--Filas-->
                                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                <?php include '../components/beneficios/gethistorico.php'; ?>
               
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

document.getElementById("registrarbeneficio").addEventListener("click", function () {
    // Mostrar un SweetAlert para capturar los datos del beneficio
    Swal.fire({
        title: 'Registrar Beneficio',
        html: `
            <input type="text" id="nombre_beneficio" class="swal2-input" placeholder="Nombre del Beneficio">
            <input type="date" id="fecha_suministrado" class="swal2-input" placeholder="Fecha Suministrada">
        `,
        confirmButtonText: 'Registrar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const nombreBeneficio = document.getElementById('nombre_beneficio').value;
            const fechaSuministrado = document.getElementById('fecha_suministrado').value;

            // Validar que los campos no estén vacíos
            if (!nombreBeneficio || !fechaSuministrado) {
                Swal.showValidationMessage('Por favor, completa todos los campos');
            }

            return { nombreBeneficio, fechaSuministrado };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar la solicitud AJAX para registrar el beneficio
            const { nombreBeneficio, fechaSuministrado } = result.value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../components/beneficios/crearbeneficios.php", true); // Cambia la ruta al archivo PHP
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);

                    // Mostrar un mensaje basado en la respuesta del servidor
                    if (response.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: response.message,
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                }
            };

            // Enviar los datos al servidor
            xhr.send(`nombre_beneficio=${encodeURIComponent(nombreBeneficio)}&fecha_suministrado=${encodeURIComponent(fechaSuministrado)}`);
        }
    });
});


    document.getElementById("mostrarbeneficios").addEventListener("click", function() {
    // Realizar la solicitud AJAX para obtener los beneficios
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../components/beneficios/getbeneficios.php", true); // Cambia esta URL según corresponda
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Parsear la respuesta JSON
            var response = JSON.parse(xhr.responseText);

            // Crear un mensaje con los beneficios
            var message = '';

            // Verificar si hay beneficios registrados
            if (response.length > 0) {
                response.forEach(function(benefit) {
                    message += '<strong>' + benefit.nombre_beneficio + '</strong><br>' +
                               'Fecha suministrada: ' + (benefit.fecha_suministrados || 'No disponible') + '<br><br>';
                });

                // Mostrar los beneficios con SweetAlert
                Swal.fire({
                    title: 'Beneficios Registrados',
                    html: message,
                    icon: 'info'
                });
            } else {
                // Si no hay beneficios registrados
                Swal.fire({
                    title: 'No hay beneficios registrados',
                    text: 'No se encontraron beneficios en la base de datos.',
                    icon: 'warning'
                });
            }
        }
    };
    xhr.send();
});

// Esperamos que el documento esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Evento para el botón "Registrar Entrega"
    document.getElementById('registrarentrega').addEventListener('click', function () {
        // Mostrar la ventana emergente (SweetAlert2)
        Swal.fire({
            title: 'Registrar Entrega',
            html: `
                <div class="swal-content">
                    <div class="form-group">
                        <label for="manzana">Manzana:</label>
                        <select id="manzana" class="swal2-input"></select>
                    </div>
                    <div class="form-group">
                        <label for="casa">Casa:</label>
                        <select id="casa" class="swal2-input"></select>
                    </div>
                    <div class="form-group">
                        <label for="grupo_familiar">Grupo Familiar:</label>
                        <select id="grupo_familiar" class="swal2-input"></select>
                    </div>
                    <div class="form-group">
                        <label for="beneficio">Beneficio:</label>
                        <select id="beneficio" class="swal2-input"></select>
                    </div>
                    <div class="form-group">
                        <label for="observacion">Observación:</label>
                        <textarea id="observacion" class="swal2-textarea" placeholder="Escribe una observación..."></textarea>
                    </div>
                </div>
            `,
            focusConfirm: false,
            preConfirm: () => {
                return [
                    document.getElementById('manzana').value,
                    document.getElementById('casa').value,
                    document.getElementById('grupo_familiar').value,
                    document.getElementById('beneficio').value,
                    document.getElementById('observacion').value
                ]
            },
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Registrar',
            customClass: {
                content: 'swal-content'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Obtener los valores seleccionados
                const idManzana = result.value[0];
                const idCasa = result.value[1];
                const idGrupoFamiliar = result.value[2];
                const idBeneficio = result.value[3];
                const observacion = result.value[4];

                // Verificar que todos los campos estén seleccionados
                if (idManzana && idCasa && idGrupoFamiliar && idBeneficio && observacion) {
                    // Enviar los datos al servidor
                    fetch('../components/beneficios/registrar_entrega.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_manzana=${idManzana}&id_casa=${idCasa}&id_grupo_familiar=${idGrupoFamiliar}&id_beneficio=${idBeneficio}&observacion=${observacion}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Éxito!', 'La entrega ha sido registrada.', 'success');
                        } else {
                            Swal.fire('Error', 'Hubo un problema al registrar la entrega.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un error al enviar los datos.', 'error');
                    });
                } else {
                    Swal.fire('Campos incompletos', 'Por favor, selecciona todos los campos.', 'warning');
                }
            }
        });

        // Cargar datos en los selectores
        cargarDatosSelectores();
    });
});

// Función para cargar los datos de los selectores (manzanas, casas, etc.)
function cargarDatosSelectores() {
    // Obtener las listas de manzanas, casas, grupos familiares y beneficios
    fetch('../components/beneficios/datos_entregas.php') // Asegúrate de que este archivo PHP devuelva los datos en formato JSON
        .then(response => response.json())
        .then(data => {
            // Obtener los selectores del DOM
            const manzanaSelect = document.getElementById('manzana');
            const casaSelect = document.getElementById('casa');
            const grupoFamiliarSelect = document.getElementById('grupo_familiar');
            const beneficioSelect = document.getElementById('beneficio');

            // Llenar el select de manzanas
            manzanaSelect.innerHTML = '<option value="">Seleccione una opción</option>';
            data.manzanas.forEach(manzana => {
                const option = document.createElement('option');
                option.value = manzana.id_manzana; // Usar id_manzana según los datos del PHP
                option.textContent = `Manzana ${manzana.nro_manzana}`; // Mostrar nro_manzana
                manzanaSelect.appendChild(option);
            });

            // Evento para cambiar las casas cuando se selecciona una manzana
            manzanaSelect.addEventListener('change', function () {
                const idManzana = this.value;
                fetch(`../components/beneficios/datos_entregas.php?id_manzana=${idManzana}`)
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar y llenar casas
                        casaSelect.innerHTML = '<option value="">Seleccione una opción</option>';
                        data.casas.forEach(casa => {
                            const option = document.createElement('option');
                            option.value = casa.id_casa;
                            option.textContent = `Casa ${casa.nro_casa}`;
                            casaSelect.appendChild(option);
                        });
                    });
            });

            // Evento para cambiar los grupos familiares cuando se selecciona una casa
            casaSelect.addEventListener('change', function () {
                const idCasa = this.value;
                fetch(`../components/beneficios/datos_entregas.php?id_casa=${idCasa}`)
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar y llenar grupos familiares
                        grupoFamiliarSelect.innerHTML = '<option value="">Seleccione una opción</option>';
                        data.grupos_familiares.forEach(grupo => {
                            const option = document.createElement('option');
                            option.value = grupo.id_persona;
                            option.textContent = `${grupo.nombre_familia} - ${grupo.primer_nombre}`;
                            grupoFamiliarSelect.appendChild(option);
                        });
                    });
            });

            // Llenar el select de beneficios
            beneficioSelect.innerHTML = '<option value="">Seleccione una opción</option>';
            data.beneficios.forEach(beneficio => {
                const option = document.createElement('option');
                option.value = beneficio.id_beneficio; // Usar id_beneficio según los datos del PHP
                option.textContent = beneficio.nombre_beneficio; // Mostrar nombre_beneficio
                beneficioSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Hubo un error al cargar los datos.', 'error');
        });
}


</script>


</body>

</html>