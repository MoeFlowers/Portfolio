<?php
session_start();
require_once '../../components/database/database.php';
$conn = getDBConnection();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$id_paciente = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_paciente <= 0) {
    die("ID de paciente no válido");
}

// Consulta para obtener datos del paciente
$sql_paciente = "SELECT *, primer_nombre as primer_nombre, segundo_nombre as segundo_nombre, 
                primer_apellido as primer_apellido, segundo_apellido as segundo_apellido, antecedentes, observaciones_dentales
                FROM pacientes WHERE id_paciente = ?";
$stmt_paciente = $conn->prepare($sql_paciente);
$stmt_paciente->bind_param("i", $id_paciente);
$stmt_paciente->execute();
$resultado_paciente = $stmt_paciente->get_result();
$patientData = $resultado_paciente->fetch_assoc();

if (!$patientData) {
    die("Paciente no encontrado");
}

// Calcular edad
if (!empty($patientData['fecha_nacimiento'])) {
    $birthDate = new DateTime($patientData['fecha_nacimiento']);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    $patientData['age'] = $age;
}

// Verificar si tiene historial
$sql_check_historial = "SELECT COUNT(*) as total FROM historias_clinicas WHERE id_paciente = ?";
$stmt_check = $conn->prepare($sql_check_historial);
$stmt_check->bind_param("i", $id_paciente);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$has_history = $result_check->fetch_assoc()['total'] > 0;

// Si tiene historial, cargarlo
// Cargar historial clínico y dientes tratados
$sql_historial = "
    SELECT 
        h.id_historia,
        h.fecha_consulta,
        h.motivo_consulta,
        h.diagnostico,
        h.plan_tratamiento,
        h.observaciones,
        h.estado,
        CONCAT(e.primer_nombre, ' ', e.primer_apellido) AS nombre_completo_odontologo
    FROM historias_clinicas h
    LEFT JOIN empleados e ON h.id_empleado = e.id_empleado
    WHERE h.id_paciente = ?
    ORDER BY h.fecha_consulta DESC";

$stmt = $conn->prepare($sql_historial);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$resultado = $stmt->get_result();

$historial_clinico = [];

while ($fila = $resultado->fetch_assoc()) {
    // Obtener detalles completos de los dientes tratados
    $sql_dientes = "SELECT * FROM dientes WHERE id_historia = ?";
    $stmt_dientes = $conn->prepare($sql_dientes);
    $stmt_dientes->bind_param("i", $fila['id_historia']);
    $stmt_dientes->execute();
    $res_dientes = $stmt_dientes->get_result();

    $dientes_detalle = [];
    while ($diente = $res_dientes->fetch_assoc()) {
        $dientes_detalle[] = $diente;
    }

    $fila['dientes_detalle'] = $dientes_detalle;
    $historial_clinico[] = $fila;
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Historial Clínico Dental</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/jquery.dataTables.min.css">
    <style>
        .tooth-marker {
            transition: all 0.2s ease;
        }

        .tooth-marker.selected {
            background-color: rgba(59, 130, 246, 0.5) !important;
        }

        #toothOverlay {
            pointer-events: auto;
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
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 space-beetween flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                    <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                </svg>
                                Historial Clínico Dental
                            </h1>
                            <nav class="flex mt-2" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    <li>
                                        <div>
                                            <a href="../../views/pacientes/pacientes.php" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                                Pacientes
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                            <span class="ml-2 text-sm font-medium text-gray-700">
                                                <?php echo htmlspecialchars($patientData['primer_nombre']) . ' ' . htmlspecialchars($patientData['primer_apellido']); ?>
                                            </span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="flex space-x-2">
                            <div class="flex space-x-2">

                                <a href="registro_dental.php?id=<?php echo $id_paciente; ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                                        <path fill-rule="evenodd" d="M12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                    Registro Dental
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Información del paciente -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-16 w-16 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo urlencode($patientData['primer_nombre']) . '+' . urlencode($patientData['primer_apellido']); ?>&background=random" alt="">
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($patientData['primer_nombre']) . ' ' . htmlspecialchars($patientData['primer_apellido']); ?></h2>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($patientData['cedula']); ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mr-2">
                                            <path d="m15 1.784-.796.795a1.125 1.125 0 1 0 1.591 0L15 1.784ZM12 1.784l-.796.795a1.125 1.125 0 1 0 1.591 0L12 1.784ZM9 1.784l-.796.795a1.125 1.125 0 1 0 1.591 0L9 1.784ZM9.75 7.547c.498-.021.998-.035 1.5-.042V6.75a.75.75 0 0 1 1.5 0v.755c.502.007 1.002.021 1.5.042V6.75a.75.75 0 0 1 1.5 0v.88l.307.022c1.55.117 2.693 1.427 2.693 2.946v1.018a62.182 62.182 0 0 0-13.5 0v-1.018c0-1.519 1.143-2.829 2.693-2.946l.307-.022v-.88a.75.75 0 0 1 1.5 0v.797ZM12 12.75c-2.472 0-4.9.184-7.274.54-1.454.217-2.476 1.482-2.476 2.916v.384a4.104 4.104 0 0 1 2.585.364 2.605 2.605 0 0 0 2.33 0 4.104 4.104 0 0 1 3.67 0 2.605 2.605 0 0 0 2.33 0 4.104 4.104 0 0 1 3.67 0 2.605 2.605 0 0 0 2.33 0 4.104 4.104 0 0 1 2.585-.364v-.384c0-1.434-1.022-2.7-2.476-2.917A49.138 49.138 0 0 0 12 12.75ZM21.75 18.131a2.604 2.604 0 0 0-1.915.165 4.104 4.104 0 0 1-3.67 0 2.605 2.605 0 0 0-2.33 0 4.104 4.104 0 0 1-3.67 0 2.605 2.605 0 0 0-2.33 0 4.104 4.104 0 0 1-3.67 0 2.604 2.604 0 0 0-1.915-.165v2.494c0 1.035.84 1.875 1.875 1.875h15.75c1.035 0 1.875-.84 1.875-1.875v-2.494Z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">
                                            <?php echo !empty($patientData['fecha_nacimiento']) ? date('d/m/Y', strtotime($patientData['fecha_nacimiento'])) . ' (' . $patientData['age'] . ' años)' : 'No especificado'; ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mr-2">
                                            <path d="M10.5 1.875a1.125 1.125 0 0 1 2.25 0v8.219c.517.162 1.02.382 1.5.659V3.375a1.125 1.125 0 0 1 2.25 0v10.937a4.505 4.505 0 0 0-3.25 2.373 8.963 8.963 0 0 1 4-.935A.75.75 0 0 0 18 15v-2.266a3.368 3.368 0 0 1 .988-2.37 1.125 1.125 0 0 1 1.591 1.59 1.118 1.118 0 0 0-.329.79v3.006h-.005a6 6 0 0 1-1.752 4.007l-1.736 1.736a6 6 0 0 1-4.242 1.757H10.5a7.5 7.5 0 0 1-7.5-7.5V6.375a1.125 1.125 0 0 1 2.25 0v5.519c.46-.452.965-.832 1.5-1.141V3.375a1.125 1.125 0 0 1 2.25 0v6.526c.495-.1.997-.151 1.5-.151V1.875Z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">
                                            Alergias: <?php echo !empty($patientData['alergias']) ? htmlspecialchars($patientData['alergias']) : 'Ninguna conocida'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Contacto</h3>
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mr-2">
                                                <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-gray-600"><?php echo !empty($patientData['telefono']) ? htmlspecialchars($patientData['telefono']) : 'No especificado'; ?></span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mr-2">
                                                <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                                <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                                            </svg>
                                            <span class="text-sm text-gray-600"><?php echo !empty($patientData['correo']) ? htmlspecialchars($patientData['correo']) : 'No especificado'; ?></span>
                                        </div>
                                        <div class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mr-2">
                                                <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-gray-600"><?php echo !empty($patientData['direccion']) ? htmlspecialchars($patientData['direccion']) : 'No especificada'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de procedimientos -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 space-beetween flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                </svg>
                                <?php echo $has_history ? 'Registro de Procedimientos' : 'Registro de Procedimientos'; ?>
                            </h3>
                        </div>

                        <?php if ($has_history): ?>
                            <!-- Mostrar historial existente -->
                            <div class="divide-y divide-gray-200">
                                <?php foreach ($historial_clinico as $registro): ?>
                                    <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    <?= date('d/m/Y H:i', strtotime($registro['fecha_consulta'])) ?>
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <span class="font-medium">Motivo:</span> <?= htmlspecialchars($registro['motivo_consulta']) ?>
                                                </p>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <!-- Botón para editar estado -->
                                                <button onclick="openEditModal(<?= $registro['id_historia'] ?>, '<?= $registro['estado'] ?>')"
                                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-md hover:bg-yellow-200 transition-colors flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                    Editar Estado
                                                </button>

                                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    <?= $registro['estado'] === 'Finalizado' ? 'bg-green-100 text-green-800' : ($registro['estado'] === 'En proceso' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') ?>">
                                                    <?= $registro['estado'] ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700">Diagnóstico</h5>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?= !empty($registro['diagnostico']) ? htmlspecialchars($registro['diagnostico']) : 'No especificado' ?>
                                                </p>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700">Plan de Tratamiento</h5>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?= !empty($registro['plan_tratamiento']) ? htmlspecialchars($registro['plan_tratamiento']) : 'No especificado' ?>
                                                </p>
                                            </div>
                                        </div>

                                        <?php
                                        // Consulta para obtener los dientes de este registro
                                        $sql_dientes = "SELECT * FROM dientes WHERE id_historia = ?";
                                        $stmt_dientes = $conn->prepare($sql_dientes);
                                        $stmt_dientes->bind_param("i", $registro['id_historia']);
                                        $stmt_dientes->execute();
                                        $dientes = $stmt_dientes->get_result()->fetch_all(MYSQLI_ASSOC);

                                        if (!empty($dientes)): ?>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700">Dientes involucrados</h5>
                                                <div class="flex flex-wrap gap-2 mt-2" id="dientes-container-<?= $registro['id_historia'] ?>">
                                                    <?php foreach ($dientes as $diente): ?>
                                                        <button
                                                            onclick="showToothDetails(<?= htmlspecialchars(json_encode($diente), ENT_QUOTES, 'UTF-8') ?>)"
                                                            class="tooth-btn px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium transition-colors cursor-pointer hover:bg-blue-200 hover:shadow-md active:bg-blue-300"
                                                            data-tooth="<?= $diente['numero_diente'] ?>"
                                                            title="Ver detalles del diente <?= $diente['numero_diente'] ?>">
                                                            <?= htmlspecialchars($diente['numero_diente']) ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($registro['observaciones'])): ?>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-medium text-gray-700">Observaciones</h5>
                                                <p class="mt-1 text-sm text-gray-600"><?= htmlspecialchars($registro['observaciones']) ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mt-4">
                                            <h5 class="text-sm font-medium text-gray-700">Atendido por</h5>
                                            <p class="mt-1 text-sm text-gray-600">
                                                <?= !empty($registro['nombre_completo_odontologo']) ?
                                                    htmlspecialchars($registro['nombre_completo_odontologo']) : 'Odontólogo no especificado' ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Mostrar mensaje si no hay historial -->
                            <div class="px-4 py-5 sm:p-6 text-center">
                                <p class="text-lg text-gray-700">Este paciente no tiene historial clínico.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Notas médicas y observaciones -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 space-beetween flex items-center">
                            <input type="hidden" id="patientId" value="<?= $id_paciente ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                                <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z" />
                                <path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z" />
                            </svg>
                            Notas Médicas y Observaciones
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="medicalNotes" class="block text-sm font-medium text-gray-700 mb-1">Antecedentes Médicos</label>
                                <textarea id="medicalNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($patientData['antecedentes']) ?></textarea>
                            </div>
                            <div>
                                <label for="dentalNotes" class="block text-sm font-medium text-gray-700 mb-1">Observaciones Dentales</label>
                                <textarea id="dentalNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($patientData['observaciones_dentales']) ?></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 space-beetween flex items-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1" id="saveNotesButton" onclick="guardarNotas()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2 text-white">
                                        <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>
                                    Guardar Notas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal para editar estado -->
    <div id="editStatusModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Editar Estado del Registro</h3>
                    <form id="editStatusForm">
                        <input type="hidden" id="editHistoryId" name="id_historia">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                            <select name="estado" id="editStatusSelect" class="block w-full bg-gray-100 border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="Pendiente">Pendiente</option>
                                <option value="En proceso">En proceso</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="updateStatus()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar Cambios
                    </button>
                    <button type="button" onclick="closeModal('editStatusModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del diente -->
    <div id="toothDetailModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Detalles del Diente <span id="toothNumber"></span>
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipo:</p>
                            <p id="toothType" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Estado:</p>
                            <p id="toothState" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tratamiento:</p>
                            <p id="toothTreatment" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Observaciones:</p>
                            <p id="toothObservations" class="text-sm text-gray-900"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeModal('toothDetailModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.tailwindcss.min.js"></script>
    <script>
        function guardarNotas() {
            const patientId = document.getElementById("patientId").value;
            const medicalNotes = document.getElementById("medicalNotes").value;
            const dentalNotes = document.getElementById("dentalNotes").value;

            fetch('../../components/historial/guardar_notas.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_paciente: patientId,
                        antecedentes_medicos: medicalNotes,
                        observaciones_dentales: dentalNotes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Notas guardadas exitosamente');
                        location.reload(); // Recargar página para ver cambios
                    } else {
                        alert('Error al guardar las notas');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al enviar los datos');
                });
        }
    </script>
    <script>
        // Función para mostrar detalles del diente
        function showToothDetails(diente) {
            // Crear el modal si no existe
            if (!document.getElementById('tooth-detail-modal')) {
                const modalHTML = `
        <div id="tooth-detail-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Detalles del Diente <span id="modal-tooth-number" class="font-bold"></span>
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tipo:</p>
                                <p id="modal-tooth-type" class="text-sm text-gray-900"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Estado:</p>
                                <p id="modal-tooth-state" class="text-sm text-gray-900"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tratamiento:</p>
                                <p id="modal-tooth-treatment" class="text-sm text-gray-900"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Observaciones:</p>
                                <p id="modal-tooth-observations" class="text-sm text-gray-900"></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="closeToothModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }

            // Llenar el modal con los datos
            document.getElementById('modal-tooth-number').textContent = diente.numero_diente;
            document.getElementById('modal-tooth-type').textContent = diente.tipo_diente || 'No especificado';
            document.getElementById('modal-tooth-state').textContent = diente.estado || 'No especificado';
            document.getElementById('modal-tooth-treatment').textContent = diente.tratamiento || 'No especificado';
            document.getElementById('modal-tooth-observations').textContent = diente.observaciones || 'No hay observaciones';

            // Mostrar el modal
            document.getElementById('tooth-detail-modal').classList.remove('hidden');
        }

        function closeToothModal() {
            document.getElementById('tooth-detail-modal').classList.add('hidden');
        }

        // Asegurar que los clicks funcionen incluso si el modal no estaba cargado inicialmente
        document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('tooth-btn')) {
                        const toothData = JSON.parse(e.target.getAttribute('data-tooth-data') || {}; showToothDetails(toothData);
                        }
                    });
    </script>
    <script>
        // Función para mostrar detalles del diente
        function showToothDetails(diente) {
            document.getElementById('toothNumber').textContent = diente.numero_diente;
            document.getElementById('toothType').textContent = diente.tipo_diente || 'No especificado';
            document.getElementById('toothState').textContent = diente.estado || 'No especificado';
            document.getElementById('toothTreatment').textContent = diente.tratamiento || 'No especificado';
            document.getElementById('toothObservations').textContent = diente.observaciones || 'No hay observaciones';

            document.getElementById('toothDetailModal').classList.remove('hidden');
        }

        // Función para cerrar modales (reutilizable)
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Opcional: Resaltar diente al pasar el mouse
        document.querySelectorAll('.tooth-number').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.classList.add('bg-blue-200');
            });
            btn.addEventListener('mouseleave', function() {
                this.classList.remove('bg-blue-200');
            });
        });
    </script>
    <script>
        // Función para abrir el modal de edición
        function openEditModal(idHistoria, currentStatus) {
            document.getElementById('editHistoryId').value = idHistoria;
            document.getElementById('editStatusSelect').value = currentStatus;
            document.getElementById('editStatusModal').classList.remove('hidden');
        }

        // Función para actualizar el estado
        function updateStatus() {
            const formData = new FormData(document.getElementById('editStatusForm'));
            const idHistoria = formData.get('id_historia');
            const nuevoEstado = formData.get('estado');

            fetch('../../components/historial/actualizar_estado.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        id_historia: idHistoria,
                        estado: nuevoEstado
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recargar la página para ver los cambios
                        location.reload();
                    } else {
                        alert('Error al actualizar el estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el estado');
                });
        }

        // Función para cerrar modales
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Manejo de la selección de dientes
        $(document).ready(function() {
            // Array para almacenar dientes tratados
            const treatedTeeth = <?php echo isset($patientData['dientes_tratados']) ?
                                        json_encode(explode(', ', $patientData['dientes_tratados'])) : '[]'; ?>;

            // Resaltar dientes ya tratados al cargar la página
            treatedTeeth.forEach(toothNum => {
                $(`.tooth-marker[data-tooth="${toothNum}"]`).addClass('bg-green-100 bg-opacity-50 treated');
            });

            // Manejo de la selección de dientes
            $('.tooth-marker').click(function() {
                const toothNumber = $(this).data('tooth');
                const teethInput = $('#teethInvolved');
                let currentTeeth = teethInput.val() ? teethInput.val().split(/\s*,\s*/) : [];

                // Si el diente ya está marcado como tratado, no permitir selección
                if ($(this).hasClass('treated')) return;

                // Alternar selección
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected bg-blue-100 bg-opacity-50');
                    currentTeeth = currentTeeth.filter(t => t != toothNumber);
                } else {
                    $(this).addClass('selected bg-blue-100 bg-opacity-50');
                    currentTeeth.push(toothNumber);
                }

                // Actualizar campo de texto
                teethInput.val(currentTeeth.join(', '));
            });

            // Actualizar desde el campo de texto
            $('#teethInvolved').on('input', function() {
                const teethNumbers = $(this).val().split(/\s*,\s*/);

                // Limpiar selecciones (excepto tratados)
                $('.tooth-marker').not('.treated').removeClass('selected bg-blue-100 bg-opacity-50');

                // Marcar los nuevos
                teethNumbers.forEach(num => {
                    if (num) {
                        $(`.tooth-marker[data-tooth="${num.trim()}"]`)
                            .not('.treated')
                            .addClass('selected bg-blue-100 bg-opacity-50');
                    }
                });
            });
        });
    </script>
</body>

</html>