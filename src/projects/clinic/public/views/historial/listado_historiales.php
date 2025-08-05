<?php
session_start();
require_once '../../components/database/database.php';
$conn = getDBConnection();

// Consulta para obtener todos los pacientes y verificar si tienen historial
$sql = "SELECT p.id_paciente, 
               CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_paciente,
               p.cedula,
               IF(COUNT(h.id_historia) > 0, 'No', 'Sí') as primera_vez
        FROM pacientes p
        LEFT JOIN historias_clinicas h ON p.id_paciente = h.id_paciente
        GROUP BY p.id_paciente
        ORDER BY p.fecha_registro DESC";

$result = $conn->query($sql);
$patients = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Historiales Clínicos</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"  rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <style>
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-finalizado {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .status-proceso {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .status-pendiente {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        #historialesTable tbody tr:hover {
            background-color: #f0f9ff;
        }
        .action-btn {
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
        .view-btn {
            background-color: #DCFCE7;
            color: #166534;
        }
        .view-btn:hover {
            background-color: #BBF7D0;
        }
        .dental-btn {
            background-color: #E0E7FF;
            color: #4338CA;
        }
        .dental-btn:hover {
            background-color: #C7D2FE;
        }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include '../../components/dashboard/sidebar.php'; ?>
    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875ZM12.75 12a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V18a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V12Z" clip-rule="evenodd" />
                            </svg>
                            Listado de Historiales Clínicos
                        </h1>
                        <nav class="flex mt-2" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2">
                                <li>
                                    <div>
                                        <a href="../../views/dashboard/dashboard.php" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                            Inicio
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                        <span class="ml-2 text-sm font-medium text-gray-700">
                                            Historiales
                                        </span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Tabla de pacientes con historial -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table id="historialesTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Paciente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Cédula</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Primera Vez</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-blue-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($patient['nombre_paciente']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($patient['cedula']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?= $patient['primera_vez'] ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <!-- Botón Ver Historial -->
                                                <button onclick="viewHistory(<?= $patient['id_paciente'] ?>)"
                                                    class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1"
                                                    title="Ver historial">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                        <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875ZM12.75 12a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V18a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V12Z" clip-rule="evenodd" />
                                                        <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script> 
<script>
    $(document).ready(function() {
        $('#historialesTable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' 
            },
            columnDefs: [{
                orderable: false,
                targets: [3] // Columna de acciones no ordenable
            }],
            order: []
        });
    });

    function viewHistory(id) {
        window.location.href = `../../views/historial/historial.php?id=${id}`;
    }
</script>
</body>
</html>