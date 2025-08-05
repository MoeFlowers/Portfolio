<?php
session_start();
require_once '../../components/database/database.php';
$conn = getDBConnection();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Dashboard</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../public/assets/css/all.min.css">
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
                    <!-- Welcome Banner -->
                    <?php include '../../components/dashboard/welcome-banner.php'; ?>
                    <!-- Stats Cards -->
                    <?php include '../../components/dashboard/stats-cards.php'; ?>
                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Citas próximas -->
                        <div class="lg:col-span-2">
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 space-between flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600 mr-2">
                                            <path d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                                            <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                                        </svg>
                                        Citas próximas
                                    </h3>
                                    <a href="../../views/citas/citas.php" class="text-sm text-blue-600 hover:text-blue-800">Ver todas</a>
                                </div>
                                <div class="divide-y divide-gray-200">
                                    <?php
                                    $stmt = $conn->prepare("
        SELECT c.fecha_hora, p.primer_nombre, p.primer_apellido, p.cedula, c.estado, pr.nombre_procedimiento
        FROM citas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        JOIN procedimientos pr ON c.id_procedimiento = pr.id_procedimiento
        WHERE c.fecha_hora >= NOW()
        AND c.estado = 'Programada'
        ORDER BY c.fecha_hora ASC
        LIMIT 5
    ");
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($row = $result->fetch_assoc()):
                                        $fecha = new DateTime($row['fecha_hora']);
                                        $dia = $fecha->format('d M');
                                        $hora = $fecha->format('h:i A');
                                    ?>
                                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">

                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($row['primer_nombre'] . ' ' . $row['primer_apellido']) ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($row['nombre_procedimiento']) ?></div>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= $hora ?></div>
                                                    <div class="text-sm text-gray-500"><?= $dia ?></div>
                                                </div>
                                                <div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <?= htmlspecialchars($row['estado']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Pacientes recientes -->
                        <div>
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 space-between flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                                        </svg>

                                        Pacientes recientes
                                    </h3>
                                </div>
                                <div class="divide-y divide-gray-200">
                                    <?php
                                    $result = $conn->query("SELECT * FROM pacientes ORDER BY fecha_registro DESC LIMIT 4");
                                    while ($p = $result->fetch_assoc()):
                                        $registro = new DateTime($p['fecha_registro']);
                                        $registro = $registro->format('j \d\e F');
                                    ?>
                                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($p['primer_nombre'] . ' ' . $p['primer_apellido']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($p['cedula']) ?></div>
                                                </div>
                                                <div class="ml-auto">
                                                    <span class="text-xs text-gray-500"><?= $registro ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <div class="px-4 py-4 sm:px-6 border-t border-gray-200 text-center">
                                    <a href="../../views/pacientes/pacientes.php" class="text-sm text-blue-600 hover:text-blue-800">Ver todos los pacientes</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de citas -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center space-between">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                                    </svg>
                                    Citas mensuales
                                </h3>
                                <select id="filtro-meses" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="6">Últimos 6 meses</option>
                                    <option value="12">Este año</option>
                                    <option value="24">Último año</option>
                                </select>
                            </div>

                            <!-- Contenedor del gráfico -->
                            <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
                                <canvas id="citasMensualesChart" width="400" height="300"></canvas>
                            </div>
                        </div>





                        <!-- Procedimientos comunes -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center space-between">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                    <path d="M6 3a3 3 0 0 0-3 3v2.25a3 3 0 0 0 3 3h2.25a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3H6ZM15.75 3a3 3 0 0 0-3 3v2.25a3 3 0 0 0 3 3H18a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3h-2.25ZM6 12.75a3 3 0 0 0-3 3V18a3 3 0 0 0 3 3h2.25a3 3 0 0 0 3-3v-2.25a3 3 0 0 0-3-3H6ZM17.625 13.5a.75.75 0 0 0-1.5 0v2.625H13.5a.75.75 0 0 0 0 1.5h2.625v2.625a.75.75 0 0 0 1.5 0v-2.625h2.625a.75.75 0 0 0 0-1.5h-2.625V13.5Z" />
                                </svg>
                                Procedimientos más solicitados
                            </h3>
                            <?php
                            // Obtener los procedimientos más comunes desde la BD
                            $stmt = $conn->prepare("
        SELECT p.nombre_procedimiento, COUNT(c.id_procedimiento) AS cantidad
        FROM citas c
        JOIN procedimientos p ON c.id_procedimiento = p.id_procedimiento
        GROUP BY c.id_procedimiento
        ORDER BY cantidad DESC
        LIMIT 5
    ");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            ?>
                            <ul class="space-y-3">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-100">
                                            <span class="font-medium text-gray-800"><?= htmlspecialchars($row['nombre_procedimiento']) ?></span>
                                        </li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No hay datos disponibles.</p>
                                <?php endif; ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="../../assets/js/chart.js"></script>

    <?php
    // Consulta de citas por mes
    $meses = 6;

    $sql = "
    SELECT DATE_FORMAT(fecha_hora, '%Y-%m') AS mes, COUNT(*) AS cantidad
    FROM citas
    WHERE fecha_hora >= DATE_SUB(NOW(), INTERVAL $meses MONTH)
    GROUP BY mes
    ORDER BY mes ASC";

    $result = $conn->query($sql);

    $labels = [];
    $values = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['mes'];
        $values[] = $row['cantidad'];
    }

    $labels_js = json_encode($labels);
    $values_js = json_encode($values);
    ?>

    <script>
        const ctx = document.getElementById('citasMensualesChart').getContext('2d');

        const data = {
            labels: <?= $labels_js ?>,
            datasets: [{
                label: 'Citas',
                data: <?= $values_js ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        };

        const chart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Actualizar gráfico según filtro
        document.getElementById('filtro-meses').addEventListener('change', function() {
            const meses = this.value;

            fetch(`get_citas_mensuales.php?meses=${meses}`)
                .then(response => response.json())
                .then(data => {
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.values;
                    chart.update();
                });
        });
    </script>
</body>

</html>