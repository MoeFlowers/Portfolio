<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="../output.css" rel="stylesheet" />
    <title>Bitácora</title>
</head>

<body class="font-roboto bg-gray-100 min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include '../components/aside.php'; ?>
        <div class="ml-64 flex-grow flex flex-col">
            <div class="ml-4 mt-6 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
                <section class="container px-4 mx-auto">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <!-- Título -->
                        <div>
                            <div class="flex items-center gap-x-3">
                                <h2 class="text-lg font-medium text-gray-800 dark:text-white">Histórico de Acciones</h2>
                                <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">Activo</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Últimas Acciones Realizadas</p>
                        </div>
                    </div>

                    <div class="flex flex-col mt-6">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <!-- Columnas -->
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Usuario</th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Acción</th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Descripción</th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Fecha</th>
                                            </tr>
                                        </thead>
                                        <!-- Filas -->
                                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                            <?php include '../controladores/getbitacora.php'; ?>
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
