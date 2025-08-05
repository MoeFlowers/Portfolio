<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function calcularEdad($fecha_nacimiento)
{
    if (empty($fecha_nacimiento)) return 'N/A';

    try {
        $fecha_nac = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        if ($fecha_nac > $hoy) return 'Fecha inválida';
        $edad = $hoy->diff($fecha_nac);
        return $edad->y;
    } catch (Exception $e) {
        return 'Error';
    }
}

require_once '../../components/database/database.php';
$conn = getDBConnection();

// Consulta SQL que incluye el campo tipo_sangre
$sql = "SELECT id_paciente, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, 
               cedula, telefono, correo, fecha_nacimiento, genero, tipo_sangre,
               direccion, alergias, estado, fecha_registro 
        FROM pacientes 
        ORDER BY fecha_registro ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Error al obtener pacientes: " . $conn->error);
}

$pacientes = $result->fetch_all(MYSQLI_ASSOC);

// Ordenar por fecha de registro
usort($pacientes, function ($a, $b) {
    return strtotime($a['fecha_registro']) - strtotime($b['fecha_registro']);
});
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    </style>
</head>

<body class="bg-gray-50">

    <!-- Tabla de pacientes -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table id="patientsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Nombre Completo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Cédula</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Teléfono</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Tipo Sangre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-blue-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr class="hover:bg-blue-50 transition-colors duration-150"
                            data-id="<?= $paciente['id_paciente'] ?>"
                            data-primer-nombre="<?= htmlspecialchars($paciente['primer_nombre']) ?>"
                            data-segundo-nombre="<?= htmlspecialchars($paciente['segundo_nombre'] ?? '') ?>"
                            data-primer-apellido="<?= htmlspecialchars($paciente['primer_apellido']) ?>"
                            data-segundo-apellido="<?= htmlspecialchars($paciente['segundo_apellido'] ?? '') ?>"
                            data-cedula="<?= htmlspecialchars($paciente['cedula']) ?>"
                            data-telefono="<?= htmlspecialchars($paciente['telefono']) ?>"
                            data-correo="<?= htmlspecialchars($paciente['correo'] ?? '') ?>"
                            data-fecha-nacimiento="<?= date('Y-m-d', strtotime($paciente['fecha_nacimiento'])) ?>"
                            data-genero="<?= $paciente['genero'] ?>"
                            data-tipo-sangre="<?= $paciente['tipo_sangre'] ?? '' ?>"
                            data-direccion="<?= htmlspecialchars($paciente['direccion'] ?? '') ?>"
                            data-alergias="<?= htmlspecialchars($paciente['alergias'] ?? '') ?>"
                            data-estado="<?= htmlspecialchars($paciente['estado'] ?? 'Inactivo') ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= $paciente['primer_nombre'] ?> <?= $paciente['primer_apellido'] ?>
                                        </div>
                                        <div class="text-sm text-gray-500 truncate max-w-[180px]">
                                            <?= $paciente['correo'] ?? 'Sin correo' ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $paciente['cedula'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $paciente['telefono'] ?></div>
                                <div class="text-xs text-gray-500">
                                    <?= $paciente['genero'] ?> •
                                    <?= calcularEdad($paciente['fecha_nacimiento']) ?> años
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full 
                                <?= $paciente['tipo_sangre'] ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= $paciente['tipo_sangre'] ?? 'No registrado' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full 
                                <?= $paciente['estado'] === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= htmlspecialchars($paciente['estado'] ?? 'Inactivo') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <!-- Botón Editar -->
                                <button onclick="openEditModal(<?= $paciente['id_paciente'] ?>)"
                                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Botón Eliminar -->
                                <button onclick="confirmDelete(<?= $paciente['id_paciente'] ?>)"
                                    class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                    title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <!-- Botón Ver Detalles -->
                                <button onclick="viewDetails(<?= $paciente['id_paciente'] ?>)"
                                    class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editPatientModal" class="modal">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: #1d4ed8; padding: 8px; border-radius: 6px; margin-right: 12px;">
                        <i class="fas fa-user-edit" style="color: white; font-size: 20px;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 600;">Editar Paciente</h3>
                </div>
                <button onclick="closeEditModal()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="editPatientForm" method="post" action="../../components/pacientes/actualizar_paciente.php">
                    <input type="hidden" id="edit_id_paciente" name="id_paciente">
                    
                    <div style="margin-bottom: 30px;">
                        <!-- Información Personal -->
                        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                            <h4 style="font-size: 18px; color: #111827; margin-bottom: 15px; display: flex; align-items: center;">
                                <i class="fas fa-id-card" style="color: #2563eb; margin-right: 8px;"></i>
                                Información Personal
                            </h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label for="edit_primer_nombre" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Primer Nombre*</label>
                                    <input type="text" id="edit_primer_nombre" name="primer_nombre" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label for="edit_segundo_nombre" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Segundo Nombre</label>
                                    <input type="text" id="edit_segundo_nombre" name="segundo_nombre"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label for="edit_primer_apellido" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Primer Apellido*</label>
                                    <input type="text" id="edit_primer_apellido" name="primer_apellido" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label for="edit_segundo_apellido" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Segundo Apellido</label>
                                    <input type="text" id="edit_segundo_apellido" name="segundo_apellido"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cédula*</label>
        <input type="text" id="edit_cedula" name="cedula" required readonly
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
    </div>
                                <div>
                                    <label for="edit_fecha_nacimiento" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Fecha de Nacimiento*</label>
                                    <input type="date" id="edit_fecha_nacimiento" name="fecha_nacimiento" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label for="edit_genero" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Género*</label>
                                    <select id="edit_genero" name="genero" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                        <option value="">Seleccione...</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="edit_estado" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Estado*</label>
                                    <select id="edit_estado" name="estado" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                            <h4 style="font-size: 18px; color: #111827; margin-bottom: 15px; display: flex; align-items: center;">
                                <i class="fas fa-address-book" style="color: #2563eb; margin-right: 8px;"></i>
                                Información de Contacto
                            </h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label for="edit_telefono" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Teléfono*</label>
                                    <input type="tel" id="edit_telefono" name="telefono" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: 3101234567">
                                </div>
                                <div>
                                    <label for="edit_correo" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Correo Electrónico</label>
                                    <input type="email" id="edit_correo" name="correo"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: paciente@example.com">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label for="edit_direccion" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Dirección</label>
                                    <textarea id="edit_direccion" name="direccion" rows="2"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: Calle 123 #45-67"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Información Médica -->
                        <div>
                            <h4 style="font-size: 18px; color: #111827; margin-bottom: 15px; display: flex; align-items: center;">
                                <i class="fas fa-heartbeat" style="color: #2563eb; margin-right: 8px;"></i>
                                Información Médica
                            </h4>
                            <div>
                                <label for="edit_alergias" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Alergias</label>
                                <textarea id="edit_alergias" name="alergias" rows="2"
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                    placeholder="Ej: Penicilina, mariscos, etc."></textarea>
                            </div>
                            <div style="margin-top: 15px;">
                                <label for="edit_tipo_sangre" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Tipo de Sangre*</label>
                                <select id="edit_tipo_sangre" name="tipo_sangre" required
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <option value="">Seleccione...</option>
                                    <option value="O-">O-</option>
                                    <option value="O+">O+</option>
                                    <option value="A-">A-</option>
                                    <option value="A+">A+</option>
                                    <option value="B-">B-</option>
                                    <option value="B+">B+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="AB+">AB+</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pie del modal -->
                    <div style="background-color: #f9fafb; padding: 15px 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between;">
                        <button type="button" onclick="closeEditModal()"
                            style="display: inline-flex; align-items: center; padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background-color: white; color: #374151; font-size: 14px; cursor: pointer;">
                            <i class="fas fa-times" style="margin-right: 8px;"></i> Cancelar
                        </button>
                        <button type="submit"
                            style="display: inline-flex; align-items: center; padding: 8px 16px; border: none; border-radius: 6px; background-color: #2563eb; color: white; font-size: 14px; cursor: pointer;">
                            <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            $('#patientsTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                columnDefs: [{
                    orderable: false,
                    targets: [4, 5]
                }]
            });
        });

        function openEditModal(id) {
            var row = $(`tr[data-id="${id}"]`);

            // Llenar el formulario con los datos
            $('#edit_id_paciente').val(row.attr('data-id'));
            $('#edit_primer_nombre').val(row.attr('data-primer-nombre'));
            $('#edit_segundo_nombre').val(row.attr('data-segundo-nombre'));
            $('#edit_primer_apellido').val(row.attr('data-primer-apellido'));
            $('#edit_segundo_apellido').val(row.attr('data-segundo-apellido'));
            $('#edit_cedula').val(row.attr('data-cedula'));
            $('#edit_telefono').val(row.attr('data-telefono'));
            $('#edit_correo').val(row.attr('data-correo'));
            $('#edit_fecha_nacimiento').val(row.attr('data-fecha-nacimiento'));
            $('#edit_genero').val(row.attr('data-genero'));
            $('#edit_tipo_sangre').val(row.attr('data-tipo-sangre') || '');
            $('#edit_estado').val(row.attr('data-estado'));
            $('#edit_direccion').val(row.attr('data-direccion'));
            $('#edit_alergias').val(row.attr('data-alergias'));

            // Mostrar el modal
            $('#editPatientModal').css('display', 'block');
            $('body').css('overflow', 'hidden');
        }

        function closeEditModal() {
            $('#editPatientModal').css('display', 'none');
            $('body').css('overflow', 'auto');
        }

        function viewDetails(id) {
            var row = $(`tr[data-id="${id}"]`);

            Swal.fire({
                title: 'Detalles del Paciente',
                html: `
            <div class="text-left space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium">Nombre Completo:</p>
                        <p>${row.attr('data-primer-nombre')} ${row.attr('data-segundo-nombre') || ''} ${row.attr('data-primer-apellido')} ${row.attr('data-segundo-apellido') || ''}</p>
                    </div>
                    <div>
                        <p class="font-medium">Cédula:</p>
                        <p>${row.attr('data-cedula')}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium">Teléfono:</p>
                        <p>${row.attr('data-telefono')}</p>
                    </div>
                    <div>
                        <p class="font-medium">Correo:</p>
                        <p>${row.attr('data-correo') || 'No registrado'}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium">Fecha Nacimiento:</p>
                        <p>${row.attr('data-fecha-nacimiento') || 'No registrada'}</p>
                    </div>
                    <div>
                        <p class="font-medium">Edad:</p>
                        <p>${row.find('td:nth-child(3) div.text-xs').text().split('•')[1].trim()}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium">Género:</p>
                        <p>${row.attr('data-genero')}</p>
                    </div>
                    <div>
                        <p class="font-medium">Tipo de Sangre:</p>
                        <p>${row.attr('data-tipo-sangre') || 'No registrado'}</p>
                    </div>
                </div>
                
                <div>
                    <p class="font-medium">Dirección:</p>
                    <p>${row.attr('data-direccion') || 'No registrada'}</p>
                </div>
                
                <div>
                    <p class="font-medium">Alergias:</p>
                    <p>${row.attr('data-alergias') || 'Ninguna registrada'}</p>
                </div>
            </div>
        `,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#2563eb',
                width: '700px'
            });
        }

        $('#editPatientForm').submit(function(e) {
            e.preventDefault();

            // Validación básica del formulario
            const requiredFields = ['primer_nombre', 'primer_apellido', 'cedula', 'telefono', 'tipo_sangre'];
            let isValid = true;

            requiredFields.forEach(field => {
                if (!$(`#edit_${field}`).val().trim()) {
                    isValid = false;
                    $(`#edit_${field}`).addClass('border-red-500');
                } else {
                    $(`#edit_${field}`).removeClass('border-red-500');
                }
            });

            if (!isValid) {
                Swal.fire('Error', 'Por favor complete todos los campos requeridos', 'error');
                return;
            }

            // Mostrar loader
            Swal.fire({
                title: 'Guardando cambios...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Enviar datos al servidor
            $.ajax({
                url: '../../components/pacientes/editar_paciente.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message,
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Error al guardar los cambios'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    let errorMessage = 'Error en la conexión con el servidor';
                    try {
                        const jsonResponse = JSON.parse(xhr.responseText);
                        errorMessage = jsonResponse.message || errorMessage;
                    } catch (e) {
                        errorMessage += `: ${error}`;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                    console.error('Error completo:', xhr.responseText);
                }
            });
        });

        async function confirmDelete(id) {
            const { value: confirm } = await Swal.fire({
                title: '¿Confirmar desactivación?',
                text: "El paciente se mostrará como Inactivo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            });

            if (!confirm) return;

            try {
                const response = await fetch('../../components/pacientes/desactivar_paciente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error en la solicitud');
                }

                if (data.success) {
                    await Swal.fire('¡Éxito!', data.message, 'success');
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error completo:', error);
                Swal.fire('Error', error.message, 'error');
            }
        }
    </script>

</body>

</html>