<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../components/database/database.php';
$conn = getDBConnection();

// Si no hay sesión, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Gestión de Citas</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/jquery.dataTables.min.css">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="../../assets/css/main.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../assets/css/dataTables.tailwindcss.min.css">
    <!-- Select2 CSS -->
    <link href="../../assets/css/tailwind.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #2563eb;
            color: white;
            padding: 15px 20px;
            border-radius: 6px 6px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px 0;
            max-height: 70vh;
            overflow-y: auto;
        }

        #calendar {
            margin: 0 auto;
        }

        .fc-event {
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include '../../components/dashboard/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Main Content Area -->
            <main class="p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 space-between flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                            </svg>
                            Gestión de Citas
                        </h1>
                        <button onclick="openModal('addAppointmentModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 inline-block mr-2">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                            </svg>
                            Nueva Cita
                        </button>
                    </div>

                    <!-- Pestañas de vista -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8">
                            <a href="#" id="calendarTab" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm space-between flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                                    <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                                </svg>
                                Vista Calendario
                            </a>
                            <a href="#" id="listTab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm space-between flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                                    <path fill-rule="evenodd" d="M2.625 6.75a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0A.75.75 0 0 1 8.25 6h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM2.625 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 12a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12A.75.75 0 0 1 7.5 12Zm-4.875 5.25a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                </svg>
                                Vista Lista
                            </a>
                        </nav>
                    </div>

                    <!-- Vista Calendario -->
                    <div id="calendarView">
                        <div class="bg-white shadow rounded-lg p-4 mb-6">
                            <div id="calendar"></div>
                        </div>
                    </div>

                    <!-- Vista Lista (oculta inicialmente) -->
                    <div id="listView" class="hidden">
                    </div>

                    <!-- Tabla de citas -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Listado de Citas
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="appointmentsTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Procedimiento</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Odontólogo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $stmt = $conn->prepare("
                                            SELECT c.id_cita, c.fecha_hora, c.estado,
                                                   p.primer_nombre AS paciente_nombre, p.primer_apellido AS paciente_apellido, p.cedula,
                                                   e.primer_nombre AS empleado_nombre, e.primer_apellido AS empleado_apellido,
                                                   pr.nombre_procedimiento
                                            FROM citas c
                                            JOIN pacientes p ON c.id_paciente = p.id_paciente
                                            JOIN empleados e ON c.id_empleado = e.id_empleado
                                            JOIN procedimientos pr ON c.id_procedimiento = pr.id_procedimiento
                                            ORDER BY c.fecha_hora DESC
                                        ");
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($row = $result->fetch_assoc()):
                                        $fecha = new DateTime($row['fecha_hora']);
                                        $fecha_formateada = $fecha->format('d/m/Y');
                                        $hora_formateada = $fecha->format('h:i A');
                                        $estado_clase = match ($row['estado']) {
                                            'Programada' => 'bg-blue-100 text-blue-800',
                                            'Confirmada' => 'bg-green-100 text-green-800',
                                            'Completada' => 'bg-green-100 text-green-800',
                                            'Cancelada' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($row['paciente_nombre'] . ' ' . $row['paciente_apellido']) ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($row['cedula']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= $fecha_formateada ?></div>
                                                <div class="text-sm text-gray-500"><?= $hora_formateada ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= htmlspecialchars($row['nombre_procedimiento']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= htmlspecialchars($row['empleado_nombre'] . ' ' . $row['empleado_apellido']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $estado_clase ?>">
                                                    <?= htmlspecialchars($row['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="../../components/citas/editar_cita.php?id=<?= $row['id_cita'] ?>">
                                                    <i class="fas fa-edit text-blue-600"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>

    <!-- Modal para agregar nueva cita -->
    <div id="addAppointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: #1d4ed8; padding: 8px; border-radius: 6px; margin-right: 12px;">
                        <i class="fas fa-calendar-plus" style="color: white; font-size: 20px;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 600;">Nueva Cita</h3>
                </div>
                <button onclick="closeModal('addAppointmentModal')" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <div class="modal-body">
                <form action="../../components/citas/registrar_cita.php" method="POST" class="space-y-4">
                    <!-- Selección de Paciente -->
                    <div>
                        <label for="id_paciente" class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                        <select id="id_paciente" name="id_paciente" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona un paciente</option>
                            <?php
                            $result = $conn->query("SELECT id_paciente, primer_nombre, segundo_nombre, primer_apellido, cedula FROM pacientes ORDER BY primer_nombre ASC");
                            while ($row = $result->fetch_assoc()):
                                $nombre_completo = htmlspecialchars($row['primer_nombre'] . ' ' . $row['segundo_nombre'] . ' ' . $row['primer_apellido']);
                                $cedula = htmlspecialchars($row['cedula']);
                            ?>
                                <option value="<?= $row['id_paciente'] ?>">
                                    <?= "$nombre_completo - $cedula" ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Selección de Odontólogo -->
                    <div>
                        <label for="id_empleado" class="block text-sm font-medium text-gray-700 mb-1">Odontólogo *</label>
                        <select id="id_empleado" name="id_empleado" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona un odontólogo</option>
                            <?php
                            $result = $conn->query("SELECT id_empleado, primer_nombre, primer_apellido, especialidad FROM empleados WHERE rol = 'Dentista' ORDER BY primer_nombre ASC");
                            while ($row = $result->fetch_assoc()):
                                $nombre_completo = htmlspecialchars($row['primer_nombre'] . ' ' . $row['primer_apellido']);
                                $especialidad = htmlspecialchars($row['especialidad']);
                            ?>
                                <option value="<?= $row['id_empleado'] ?>">
                                    <?= "$nombre_completo - $especialidad" ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Selección de Procedimiento -->
                    <div>
                        <label for="id_procedimiento" class="block text-sm font-medium text-gray-700 mb-1">Procedimiento *</label>
                        <select id="id_procedimiento" name="id_procedimiento" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona un procedimiento</option>
                            <?php
                            $result = $conn->query("SELECT id_procedimiento, nombre_procedimiento, descripcion FROM procedimientos ORDER BY nombre_procedimiento ASC");
                            while ($row = $result->fetch_assoc()):
                                $nombre = htmlspecialchars($row['nombre_procedimiento']);
                                $descripcion = htmlspecialchars($row['descripcion']);
                            ?>
                                <option value="<?= $row['id_procedimiento'] ?>">
                                    <?= "$nombre - $descripcion" ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                            <input type="date" id="fecha" name="fecha"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                            <input type="time" id="hora" name="hora"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select id="estado" name="estado"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="Programada">Programada</option>
                            <option value="Confirmada">Confirmada</option>
                            <option value="Completada">Completada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal('addAppointmentModal')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition duration-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            Guardar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Funciones para manejar modales
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Inicializar DataTables
        $(document).ready(function() {
            $('#appointmentsTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                dom: '<"flex justify-between items-center mb-4"<"flex"l><"flex"f>>rt<"flex justify-between items-center mt-4"<"flex"i><"flex"p>>',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                pageLength: 10
            });

            // Inicializar FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: [
                    <?php
                    $stmt = $conn->query("SELECT 
                    c.fecha_hora AS start,
                    DATE_ADD(c.fecha_hora, INTERVAL 1 HOUR) AS end,
                    c.estado,
                    CONCAT(p.primer_nombre, ' - ', pr.nombre_procedimiento) AS title
                    FROM citas c
                    JOIN pacientes p ON c.id_paciente = p.id_paciente
                    JOIN procedimientos pr ON c.id_procedimiento = pr.id_procedimiento");

                    while ($row = $stmt->fetch_assoc()) {
                        $color = match ($row['estado']) {
                            'Programada' => '#3b82f6',
                            'Confirmada' => '#10b981',
                            'Completada' => '#10b981',
                            'Cancelada' => '#f59e0b',
                            default => '#9ca3af'
                        };
                    ?> {
                            title: "<?= addslashes($row['title']) ?>",
                            start: "<?= $row['start'] ?>",
                            end: "<?= $row['end'] ?? $row['start'] ?>",
                            backgroundColor: "<?= $color ?>"
                        },
                    <?php } ?>
                ],
                eventClick: function(info) {
                    // Aquí puedes agregar lógica para editar citas desde el calendario
                    console.log('Evento clickeado:', info.event);
                }
            });
            calendar.render();

            // Navegación entre vistas
            document.getElementById('calendarTab').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('calendarView').classList.remove('hidden');
                document.getElementById('listView').classList.add('hidden');
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('listTab').classList.remove('border-blue-500', 'text-blue-600');
                document.getElementById('listTab').classList.add('border-transparent', 'text-gray-500');
            });

            document.getElementById('listTab').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('listView').classList.remove('hidden');
                document.getElementById('calendarView').classList.add('hidden');
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('calendarTab').classList.remove('border-blue-500', 'text-blue-600');
                document.getElementById('calendarTab').classList.add('border-transparent', 'text-gray-500');
            });

            // Inicializar Select2 para los select
            $('#id_paciente, #id_empleado, #id_procedimiento').select2({
                width: '100%',
                dropdownParent: $('#addAppointmentModal')
            });
        });
    </script>
</body>

</html>