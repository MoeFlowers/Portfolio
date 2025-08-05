<?php
session_start();
require_once '../../components/database/database.php'; // Asegúrate de ajustar esta ruta según tu estructura

$conn = getDBConnection();

// Verificar si se pasó el ID de la cita
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de cita no válido");
}

$id_cita = intval($_GET['id']);

// Obtener datos actuales de la cita
$stmt = $conn->prepare("
    SELECT 
        c.id_cita,
        c.id_paciente,
        c.id_empleado,
        c.id_procedimiento,
        c.fecha_hora,
        c.estado,
        c.observaciones,
        p.primer_nombre AS nombre_paciente,
        p.primer_apellido AS apellido_paciente,
        p.cedula,
        e.primer_nombre AS nombre_empleado,
        e.primer_apellido AS apellido_empleado,
        pr.nombre_procedimiento
    FROM citas c
    JOIN pacientes p ON c.id_paciente = p.id_paciente
    JOIN empleados e ON c.id_empleado = e.id_empleado
    JOIN procedimientos pr ON c.id_procedimiento = pr.id_procedimiento
    WHERE c.id_cita = ?
");

$stmt->bind_param("i", $id_cita);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No se encontró la cita");
}

$cita = $result->fetch_assoc();

// Separar fecha_hora en fecha y hora
$fecha_hora = new DateTime($cita['fecha_hora']);
$fecha = $fecha_hora->format('Y-m-d');
$hora = $fecha_hora->format('H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita - IPSFANB</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"  rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-edit mr-2 text-blue-600"></i>
            Editar Cita
        </h2>

        <!-- Formulario -->
        <form action="../../components/citas/guardar_edicion.php" method="POST" class="space-y-6">
            <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">

            <!-- Paciente -->
            <div>
                <label for="id_paciente" class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                <select id="id_paciente" name="id_paciente" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <?php
                    $pacientes = $conn->query("SELECT id_paciente, primer_nombre, segundo_nombre, primer_apellido, cedula FROM pacientes ORDER BY primer_nombre ASC");
                    while ($p = $pacientes->fetch_assoc()):
                        $nombre_completo = htmlspecialchars($p['primer_nombre'] . ' ' . $p['segundo_nombre'] . ' ' . $p['primer_apellido']);
                        $cedula = htmlspecialchars($p['cedula']);
                    ?>
                        <option value="<?= $p['id_paciente'] ?>" <?= $p['id_paciente'] == $cita['id_paciente'] ? 'selected' : '' ?>>
                            <?= "$nombre_completo - $cedula" ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Fecha y Hora -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_hora" class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                    <input type="date" id="fecha_hora" name="fecha_hora"
                           value="<?= $fecha ?>"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="hora_hora" class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                    <input type="time" id="hora_hora" name="hora_hora"
                           value="<?= $hora ?>"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Odontólogo -->
            <div>
                <label for="id_empleado" class="block text-sm font-medium text-gray-700 mb-1">Odontólogo *</label>
                <select id="id_empleado" name="id_empleado" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <?php
                    $empleados = $conn->query("SELECT id_empleado, primer_nombre, primer_apellido, especialidad FROM empleados WHERE rol = 'Dentista'");
                    while ($e = $empleados->fetch_assoc()):
                        $nombre = htmlspecialchars($e['primer_nombre'] . ' ' . $e['primer_apellido']);
                        $especialidad = htmlspecialchars($e['especialidad']);
                    ?>
                        <option value="<?= $e['id_empleado'] ?>" <?= $e['id_empleado'] == $cita['id_empleado'] ? 'selected' : '' ?>>
                            <?= "$nombre - $especialidad" ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Procedimiento -->
            <div>
                <label for="id_procedimiento" class="block text-sm font-medium text-gray-700 mb-1">Procedimiento *</label>
                <select id="id_procedimiento" name="id_procedimiento" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <?php
                    $procedimientos = $conn->query("SELECT id_procedimiento, nombre_procedimiento, descripcion FROM procedimientos");
                    while ($pr = $procedimientos->fetch_assoc()):
                        $nombre = htmlspecialchars($pr['nombre_procedimiento']);
                        $descripcion = htmlspecialchars($pr['descripcion']);
                    ?>
                        <option value="<?= $pr['id_procedimiento'] ?>" <?= $pr['id_procedimiento'] == $cita['id_procedimiento'] ? 'selected' : '' ?>>
                            <?= "$nombre - $descripcion" ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="estado" name="estado"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="Programada" <?= $cita['estado'] === 'Programada' ? 'selected' : '' ?>>Programada</option>
                    <option value="Completada" <?= $cita['estado'] === 'Completada' ? 'selected' : '' ?>>Completada</option>
                    <option value="Cancelada" <?= $cita['estado'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>

            <!-- Observaciones -->
            <div>
                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($cita['observaciones']) ?></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition duration-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>