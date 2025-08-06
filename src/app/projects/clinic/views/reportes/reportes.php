<?php
session_start();
require_once '../../components/database/database.php';
$conn = getDBConnection();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Obtener datos estadísticos
$totalCitas = $conn->query("SELECT COUNT(*) AS total FROM citas")->fetch_assoc()['total'];
$pacientesUnicos = $conn->query("SELECT COUNT(DISTINCT id_paciente) AS total FROM citas")->fetch_assoc()['total'];
$procedimientos = $conn->query("SELECT COUNT(DISTINCT id_procedimiento) AS total FROM citas")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Reportes</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../assets/css/dataTables.tailwindcss.min.css">
    <!-- Chart.js -->
    <script src="../../assets/JS/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include '../../components/dashboard/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->


            <!-- Main Content Area -->
            <main class="p-6">
                <div class="max-w-7xl mx-auto">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 space-between flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                <path fill-rule="evenodd" d="M3 2.25a.75.75 0 0 1 .75.75v.54l1.838-.46a9.75 9.75 0 0 1 6.725.738l.108.054A8.25 8.25 0 0 0 18 4.524l3.11-.732a.75.75 0 0 1 .917.81 47.784 47.784 0 0 0 .005 10.337.75.75 0 0 1-.574.812l-3.114.733a9.75 9.75 0 0 1-6.594-.77l-.108-.054a8.25 8.25 0 0 0-5.69-.625l-2.202.55V21a.75.75 0 0 1-1.5 0V3A.75.75 0 0 1 3 2.25Z" clip-rule="evenodd" />
                            </svg>

                            Reportes y Estadísticas
                        </h1>
                    </div>

                    <!-- Gráficos -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Citas por Odontólogo -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Citas por Odontólogo</h3>
                            <div class="relative h-80 bg-gray-50 rounded-lg p-4">
                                <canvas id="dentistChart"></canvas>
                            </div>
                        </div>

                        <!-- Distribución de Procedimientos -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Distribución de Procedimientos</h3>
                            <div class="relative h-96 bg-gray-50 rounded-lg p-4">
                                <canvas id="procedureChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Listado de Citas -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                            <h3 class="text-xl font-semibold text-gray-800 space-between flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                    <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                </svg>
                                Listado de Citas
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Odontólogo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Procedimiento</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $query = "SELECT 
                    c.id_cita,
                    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS paciente,
                    CONCAT(e.primer_nombre, ' ', e.primer_apellido) AS odontologo,
                    pr.nombre_procedimiento AS procedimiento,
                    c.fecha_hora,
                    c.estado
                FROM citas c
                JOIN pacientes p ON c.id_paciente = p.id_paciente
                JOIN empleados e ON c.id_empleado = e.id_empleado
                JOIN procedimientos pr ON c.id_procedimiento = pr.id_procedimiento
                ORDER BY c.fecha_hora DESC";

                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $fecha_formateada = date('d/m/Y H:i', strtotime($row['fecha_hora']));

                                            // Estilos para los estados
                                            $color_estado = '';
                                            $icon_estado = '';
                                            if ($row['estado'] == 'Programada') {
                                                $color_estado = 'bg-blue-100 text-blue-800';
                                                $icon_estado = 'far fa-clock';
                                            } elseif ($row['estado'] == 'Completada') {
                                                $color_estado = 'bg-green-100 text-green-800';
                                                $icon_estado = 'fas fa-check-circle';
                                            } elseif ($row['estado'] == 'Cancelada') {
                                                $color_estado = 'bg-red-100 text-red-800';
                                                $icon_estado = 'fas fa-times-circle';
                                            }

                                            echo "<tr class='hover:bg-gray-50'>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($row['paciente']) . "</td>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($row['odontologo']) . "</td>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($row['procedimiento']) . "</td>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . $fecha_formateada . "</td>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap'>";
                                            echo "<span class='px-2 py-1 text-xs font-semibold rounded-full $color_estado'>";
                                            echo "<i class='$icon_estado mr-1'></i>";
                                            echo htmlspecialchars($row['estado']);
                                            echo "</span>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='px-6 py-4 text-center text-sm text-gray-500'>No hay citas registradas</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Resumen estadístico -->
                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center space-between">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z" clip-rule="evenodd" />
                            </svg>

                            Resumen Estadístico
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Total Citas</p>
                                <p class="text-xl font-semibold text-gray-900 mt-1"><?= $totalCitas ?></p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Pacientes Únicos</p>
                                <p class="text-xl font-semibold text-gray-900 mt-1"><?= $pacientesUnicos ?></p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Procedimientos Diferentes</p>
                                <p class="text-xl font-semibold text-gray-900 mt-1"><?= $procedimientos ?></p>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.tailwindcss.min.js"></script>
    <!-- SheetJS para exportación avanzada a Excel -->
    <script src="../../assets/js/xlsx.full.min.js"></script>

    <!-- Configuración DataTables -->
    <script>
        $(document).ready(function() {
            var table = $('#reportTable').DataTable({
                responsive: true,
                dom: "<'flex justify-between items-center mb-4'<'flex items-center'lf><'flex items-center'ip>>" +
                    "tr" +
                    "<'flex justify-between items-center mt-4'ip>",
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                autoWidth: false,
                columns: [{
                        width: "25%"
                    },
                    {
                        width: "20%"
                    },
                    {
                        width: "25%"
                    },
                    {
                        width: "20%"
                    },
                    {
                        width: "10%"
                    }
                ],
                initComplete: function() {
                    // Conectamos nuestros controles personalizados
                    $('#tableLength').on('change', function() {
                        table.page.len($(this).val()).draw();
                    });

                    $('#tableSearch').on('keyup', function() {
                        table.search($(this).val()).draw();
                    });

                    $('#prevPage').on('click', function() {
                        table.page('previous').draw('page');
                    });

                    $('#nextPage').on('click', function() {
                        table.page('next').draw('page');
                    });
                },
                drawCallback: function() {
                    // Actualizamos la información de paginación
                    var info = this.api().page.info();
                    $('#tableInfo').html(
                        'Mostrando ' + (info.start + 1) + ' a ' + info.end + ' de ' + info.recordsTotal + ' registros'
                    );
                }
            });
        });
    </script>

    <!-- Gráficos -->
    <script>
        // Gráfico de Citas por Odontólogo
        const dentistCtx = document.getElementById('dentistChart').getContext('2d');

        // Obtener nombres de odontólogos
        const dentistLabels = [<?php
                                $res = $conn->query("SELECT CONCAT(primer_nombre, ' ', primer_apellido) AS nombre FROM empleados WHERE rol = 'Dentista'");
                                $labels = [];
                                while ($row = $res->fetch_assoc()) {
                                    $labels[] = '"' . addslashes($row['nombre']) . '"';
                                }
                                echo implode(',', $labels);
                                ?>];

        // Contar citas por odontólogo
        const appointmentCounts = [<?php
                                    $res = $conn->query("SELECT COUNT(*) AS cantidad FROM citas GROUP BY id_empleado");
                                    $counts = [];
                                    while ($row = $res->fetch_assoc()) {
                                        $counts[] = $row['cantidad'];
                                    }
                                    echo implode(',', $counts);
                                    ?>];

        new Chart(dentistCtx, {
            type: 'bar',
            data: {
                labels: dentistLabels,
                datasets: [{
                    label: 'Citas',
                    data: appointmentCounts,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Procedimientos más comunes
        const procedureCtx = document.getElementById('procedureChart').getContext('2d');

        const procedureLabels = [<?php
                                    $res = $conn->query("SELECT nombre_procedimiento FROM procedimientos");
                                    $labels = [];
                                    while ($row = $res->fetch_assoc()) {
                                        $labels[] = '"' . addslashes($row['nombre_procedimiento']) . '"';
                                    }
                                    echo implode(',', $labels);
                                    ?>];

        const procedureCounts = [<?php
                                    $res = $conn->query("SELECT COUNT(*) AS cantidad FROM citas GROUP BY id_procedimiento");
                                    $counts = [];
                                    while ($row = $res->fetch_assoc()) {
                                        $counts[] = $row['cantidad'];
                                    }
                                    echo implode(',', $counts);
                                    ?>];

        new Chart(procedureCtx, {
            type: 'doughnut',
            data: {
                labels: procedureLabels,
                datasets: [{
                    data: procedureCounts,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)', // Azul
                        'rgba(239, 68, 68, 0.7)', // Rojo
                        'rgba(16, 185, 129, 0.7)', // Verde
                        'rgba(249, 115, 22, 0.7)', // Naranja
                        'rgba(139, 92, 246, 0.7)' // Púrpura
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Funciones para imprimir y exportar -->
    <script>
        // Función para imprimir el reporte
        function printReport() {
            // Clonamos el contenido principal
            const printContent = document.querySelector('main').cloneNode(true);

            // Eliminamos elementos que no queremos imprimir
            const elementsToRemove = printContent.querySelectorAll('button, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate');
            elementsToRemove.forEach(el => el.remove());

            // Creamos una ventana de impresión
            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>IPSFANB - Reportes de Citas</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #1a365d; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f7fafc; }
                .badge { padding: 3px 6px; border-radius: 12px; font-size: 12px; }
                .bg-blue-100 { background-color: #ebf8ff; }
                .text-blue-800 { color: #2b6cb0; }
                .bg-green-100 { background-color: #f0fff4; }
                .text-green-800 { color: #276749; }
                .bg-red-100 { background-color: #fff5f5; }
                .text-red-800 { color: #9b2c2c; }
                @page { size: landscape; }
                .print-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
                .print-footer { margin-top: 30px; font-size: 12px; text-align: center; }
                .charts-container { display: flex; flex-wrap: wrap; justify-content: space-between; }
                .chart-box { width: 48%; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <div>
                    <h1>IPSFANB - Reporte de Citas Odontológicas</h1>
                    <p>Generado el: ${new Date().toLocaleDateString()}</p>
                </div>
                <img src="../../assets/images/logo.png" alt="Logo" style="height: 60px;">
            </div>
            ${printContent.innerHTML}
            <div class="print-footer">
                <p>Sistema de Gestión Odontológica - IPSFANB © ${new Date().getFullYear()}</p>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(() => { window.close(); }, 500);
                };
            <\/script>
        </body>
        </html>
    `);
            printWindow.document.close();
        }

        // Función para exportar a Excel (versión avanzada con SheetJS)
        function exportToExcelAdvanced() {
            // Obtener datos adicionales para la hoja de resumen
            const totalCitas = <?= $totalCitas ?>;
            const pacientesUnicos = <?= $pacientesUnicos ?>;
            const procedimientos = <?= $procedimientos ?>;

            // Crear libro de Excel
            const wb = XLSX.utils.book_new();

            // Hoja 1: Datos de citas
            const table = document.getElementById('reportTable');
            const ws1 = XLSX.utils.table_to_sheet(table);
            XLSX.utils.book_append_sheet(wb, ws1, "Citas");

            // Hoja 2: Resumen estadístico
            const ws2_data = [
                ["Métrica", "Valor"],
                ["Total de Citas", totalCitas],
                ["Pacientes Únicos", pacientesUnicos],
                ["Procedimientos Diferentes", procedimientos],
                ["", ""],
                ["Generado el", new Date().toLocaleString()]
            ];
            const ws2 = XLSX.utils.aoa_to_sheet(ws2_data);
            XLSX.utils.book_append_sheet(wb, ws2, "Resumen");

            // Hoja 3: Datos de gráficos (opcional)
            const ws3_data = [
                ["Citas por Odontólogo", ""],
                ...dentistLabels.map((label, i) => [label, appointmentCounts[i]]),
                ["", ""],
                ["Distribución de Procedimientos", ""],
                ...procedureLabels.map((label, i) => [label, procedureCounts[i]])
            ];
            const ws3 = XLSX.utils.aoa_to_sheet(ws3_data);
            XLSX.utils.book_append_sheet(wb, ws3, "Datos Gráficos");

            // Exportar el archivo
            XLSX.writeFile(wb, `reporte_completo_${new Date().toISOString().slice(0,10)}.xlsx`);
        }
    </script>
</body>

</html>