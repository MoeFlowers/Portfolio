<?php
session_start();
date_default_timezone_set('America/Caracas');
// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Conectar a la base de datos
require_once '../../components/database/database.php';
$conn = getDBConnection();

// Obtener ID del paciente desde la URL
$id_paciente = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_paciente <= 0) {
    die("ID de paciente no válido");
}

// Consulta para obtener datos del paciente
$sql_paciente = "SELECT 
                    primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, 
                    cedula, fecha_nacimiento, genero, tipo_sangre
                  FROM pacientes 
                  WHERE id_paciente = ?";
$stmt_paciente = $conn->prepare($sql_paciente);
$stmt_paciente->bind_param("i", $id_paciente);
$stmt_paciente->execute();
$resultado_paciente = $stmt_paciente->get_result();

$patientData = $resultado_paciente->fetch_assoc();

if (!$patientData) {
    die("Paciente no encontrado");
}

// Calcular edad
$birthDate = new DateTime($patientData['fecha_nacimiento']);
$today = new DateTime();
$age = $today->diff($birthDate)->y;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"  rel="stylesheet">
    <style>
        /* Estilos personalizados */
        .tooth-area {
            position: absolute;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 50%;
            background-color: rgba(59, 130, 246, 0.3);
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .tooth-area:hover {
            background-color: rgba(59, 130, 246, 0.5);
        }
        .tooth-area.selected {
            background-color: rgba(16, 185, 129, 0.5);
            border-color: #10b981;
            transform: scale(1.1);
        }
        .tooth-number {
            pointer-events: none;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-6xl">

    

        <!-- Formulario oculto -->
        <form id="registroForm" class="hidden">
            <input type="hidden" name="id_paciente" id="id_paciente" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
            <input type="hidden" name="fecha_consulta" id="fecha_consulta" value="<?= date('Y-m-d H:i:s') ?>">
        </form>

        <!-- Fecha de consulta (solo lectura) -->
<div class="mb-6">
    <label class="block text-gray-700 font-medium mb-2">Fecha de Consulta</label>
    <input type="text" readonly
           value="<?= date('d/m/Y H:i') ?>"
           class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-800 cursor-not-allowed">
</div>

        <!-- Información del paciente -->
<section class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold text-blue-700 mb-4">Datos del Paciente</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 mb-1">Nombre Completo</label>
            <input type="text" readonly
                   value="<?= htmlspecialchars($patientData['primer_nombre'] . ' ' . $patientData['segundo_nombre'] . ' ' . $patientData['primer_apellido'] . ' ' . $patientData['segundo_apellido']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Cédula</label>
            <input type="text" readonly
                   value="<?= htmlspecialchars($patientData['cedula']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Edad</label>
            <input type="number" readonly
                   value="<?= $age ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Género</label>
            <input type="text" readonly
                   value="<?= htmlspecialchars($patientData['genero']) ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
        </div>
        <!-- Campo nuevo: Tipo de Sangre -->
        <div>
            <label class="block text-gray-700 mb-1">Tipo de Sangre</label>
            <input type="text" readonly
                   value="<?= htmlspecialchars($patientData['tipo_sangre'] ?? 'No especificado') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
        </div>
    </div>
</section>

<!-- Formulario de Historia Clínica -->
<section id="clinical-history-form" class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-blue-700 mb-4">Historia Clínica</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 mb-1">Motivo de Consulta</label>
            <input type="text" id="motivo_consulta" placeholder="Ej: Dolor persistente"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Diagnóstico</label>
            <input type="text" id="diagnostico" placeholder="Ej: Caries profunda"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Plan de Tratamiento</label>
            <input type="text" id="plan_tratamiento" placeholder="Ej: Empaste + Limpieza"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-gray-700 mb-1">Odontólogo Responsable</label>
            <select id="odontologo" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Seleccionar odontólogo</option>
                <?php
                // Cargar odontólogos desde la base de datos
                $sql_odontologos = "SELECT id_empleado, CONCAT(primer_nombre, ' ', primer_apellido) AS nombre_completo FROM empleados WHERE rol = 'Dentista'";
                $result_odontologos = $conn->query($sql_odontologos);
                while ($row = $result_odontologos->fetch_assoc()):
                ?>
                    <option value="<?= $row['id_empleado'] ?>">
                        <?= htmlspecialchars($row['nombre_completo']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-700 mb-1">Observaciones Generales</label>
            <textarea id="observaciones" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
        </div>
    </div>
</section>

        <!-- Diagrama dental -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Diagrama Dental Interactivo</h2>
            <p class="text-gray-600 mb-4">Haz clic en un diente para registrar su estado.</p>
            <div id="dental-chart-container" class="relative mx-auto w-full h-96">
                <img src="../../assets/images/diagrama-dental.png" alt="Diagrama Dental"
                     class="w-full h-full object-contain" id="dental-image">
            </div>
        </section>

        <!-- Formulario de diente -->
        <section id="tooth-form" class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Información del Diente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Número de Diente</label>
                    <input type="text" id="tooth-number" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Tipo de Diente</label>
                    <input type="text" id="tooth-type" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Estado</label>
                    <select id="tooth-status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="Buen estado">Buen estado</option>
                        <option value="Caries">Caries</option>
                        <option value="Fractura">Fractura</option>
                        <option value="Ausente">Ausente</option>
                        <option value="Restaurado">Restaurado</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Tratamiento</label>
                    <select id="tooth-treatment" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="Ninguno">Ninguno</option>
                        <option value="Empaste">Empaste</option>
                        <option value="Corona">Corona</option>
                        <option value="Extracción">Extracción</option>
                        <option value="Endodoncia">Endodoncia</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-1">Observaciones</label>
                    <textarea id="tooth-notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="historial.php?id=<?= $_GET['id'] ?? '' ?>"
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                    Volver
                </a>
                <button id="clear-tooth"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Limpiar
                </button>
                <button id="save-tooth"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Guardar Diente
                </button>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script type="module" src="../../assets/js/historial/toothData.js"></script>
    <script type="module" src="../../assets/js/historial/app.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script>
        function getHistorialData() {
    return {
        motivo_consulta: document.getElementById('motivo_consulta').value,
        diagnostico: document.getElementById('diagnostico').value,
        plan_tratamiento: document.getElementById('plan_tratamiento').value,
        odontologo: document.getElementById('odontologo').value,
        observaciones: document.getElementById('observaciones').value
    };
}
        // Función para seleccionar diente (definida desde app.js o aquí)
        window.selectTooth = function(numero, tipo) {
            document.getElementById('tooth-number').value = numero;
            document.getElementById('tooth-type').value = tipo;

            const selectedArea = document.querySelector(`.tooth-area[data-tooth="${numero}"]`);
            document.querySelectorAll('.tooth-area').forEach(area => area.classList.remove('selected'));
            if (selectedArea) selectedArea.classList.add('selected');
        }

        document.getElementById('save-tooth').addEventListener('click', function () {
    const numero = document.getElementById('tooth-number').value;
    const tipo = document.getElementById('tooth-type').value;
    const estado = document.getElementById('tooth-status').value;
    const tratamiento = document.getElementById('tooth-treatment').value;
    const notas = document.getElementById('tooth-notes').value;

    if (!numero) {
        alert("Por favor seleccione un diente");
        return;
    }

    const id_paciente = document.getElementById('id_paciente').value;
    const fecha_consulta = document.getElementById('fecha_consulta').value;
    
    // Obtener datos del formulario de historia clínica
    const motivo_consulta = document.getElementById('motivo_consulta').value;
    const diagnostico = document.getElementById('diagnostico').value;
    const plan_tratamiento = document.getElementById('plan_tratamiento').value;
    const odontologo = document.getElementById('odontologo').value;
    const observaciones = document.getElementById('observaciones').value;

    fetch('../../components/historial/guardar_registro_dental.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id_paciente: parseInt(id_paciente),
            fecha_consulta: fecha_consulta,
            dientes: [{
                numero,
                tipo,
                estado,
                tratamiento,
                notas
            }],
            motivo_consulta: motivo_consulta,
            diagnostico: diagnostico,
            plan_tratamiento: plan_tratamiento,
            odontologo: odontologo,
            observaciones: observaciones
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Historial actualizado correctamente',
                showConfirmButton: true,
                timer: 3000
            }).then(() => {
                // Redirigir al historial del paciente después de mostrar el mensaje
                window.location.href = `historial.php?id=${id_paciente}`;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tiene que seleccionar un Odontologo: ' ,
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un problema al enviar los datos.');
    });
});

        // Limpiar campos
        document.getElementById('clear-tooth').addEventListener('click', function () {
            document.getElementById('tooth-number').value = '';
            document.getElementById('tooth-type').value = '';
            document.getElementById('tooth-status').value = 'Buen estado';
            document.getElementById('tooth-treatment').value = 'Ninguno';
            document.getElementById('tooth-notes').value = '';

            document.querySelectorAll('.tooth-area').forEach(area => {
                area.classList.remove('selected');
            });
        });
    </script>
</body>
</html>