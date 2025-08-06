<?php
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para calcular la antigüedad
function calcularAntiguedad($fecha_registro) {
    if (empty($fecha_registro)) return 'N/A';
    try {
        $fecha_reg = new DateTime($fecha_registro);
        $hoy = new DateTime();
        $antiguedad = $hoy->diff($fecha_reg);
        return $antiguedad->y > 0 ? $antiguedad->y . ' año(s)' : ($antiguedad->m > 0 ? $antiguedad->m . ' mes(es)' : $antiguedad->d . ' día(s)');
    } catch (Exception $e) {
        return 'Error';
    }
}

// Conexión a la base de datos
require_once '../../components/database/database.php';
$conn = getDBConnection();

// Consulta SQL para obtener empleados
$sql = "SELECT * FROM empleados ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
$empleados = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Empleados</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"  rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"  rel="stylesheet">

    <style>
        .badge-rol {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .bg-dentista { background-color: #BFDBFE; color: #1E40AF; }
        .bg-asistente { background-color: #A7F3D0; color: #065F46; }
        .bg-admin { background-color: #E9D5FF; color: #6B21A8; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .status-inactive {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        #editEmployeeModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        #editEmployeeModal.show {
            display: flex;
        }
        #editEmployeeModalContent {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-50">
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <!-- Tabla con ID para DataTables -->
            <table id="empleadosTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Identificación</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Rol</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase tracking-wider">Contacto</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-blue-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($empleados as $empleado): ?>
                    <tr data-id="<?= $empleado['id_empleado'] ?>"
                        data-primer-nombre="<?= htmlspecialchars($empleado['primer_nombre']) ?>"
                        data-segundo-nombre="<?= htmlspecialchars($empleado['segundo_nombre'] ?? '') ?>"
                        data-primer-apellido="<?= htmlspecialchars($empleado['primer_apellido']) ?>"
                        data-segundo-apellido="<?= htmlspecialchars($empleado['segundo_apellido'] ?? '') ?>"
                        data-cedula="<?= htmlspecialchars($empleado['cedula']) ?>"
                        data-telefono="<?= htmlspecialchars($empleado['telefono']) ?>"
                        data-correo="<?= htmlspecialchars($empleado['correo'] ?? '') ?>"
                        data-rol="<?= htmlspecialchars($empleado['rol']) ?>"
                        data-especialidad="<?= htmlspecialchars($empleado['especialidad'] ?? '') ?>"
                        data-estado="<?= htmlspecialchars($empleado['estado'] ?? 'Inactivo') ?>"
                        class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($empleado['primer_nombre'] . ' ' . $empleado['primer_apellido']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?= htmlspecialchars(($empleado['segundo_nombre'] ?? '') . ' ' . ($empleado['segundo_apellido'] ?? '')) ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= htmlspecialchars($empleado['cedula']) ?></div>
                            <div class="text-xs text-gray-500">
                                <?= htmlspecialchars(calcularAntiguedad($empleado['fecha_registro'])) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $rolClass = 'bg-gray-100 text-gray-800';
                            switch($empleado['rol']) {
                                case 'Dentista': $rolClass = 'bg-dentista'; break;
                                case 'Asistente': $rolClass = 'bg-asistente'; break;
                                case 'Administrativo': $rolClass = 'bg-admin'; break;
                            }
                            ?>
                            <span class="badge-rol <?= $rolClass ?>">
                                <?= htmlspecialchars($empleado['rol']) ?>
                            </span>
                            <div class="text-xs mt-1 text-gray-500">
                                <?= htmlspecialchars($empleado['especialidad'] ?? 'Sin especialidad') ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge <?= $empleado['estado'] === 'Activo' ? 'status-active' : 'status-inactive' ?>">
                                <?= htmlspecialchars($empleado['estado'] ?? 'Inactivo') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-phone-alt mr-1 text-blue-500"></i>
                                <?= htmlspecialchars($empleado['telefono']) ?>
                            </div>
                            <div class="text-sm text-gray-600 truncate max-w-xs">
                                <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                <?= htmlspecialchars($empleado['correo'] ?? 'Sin correo') ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button onclick="openEditEmployeeModal(<?= $empleado['id_empleado'] ?>)"
                                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="viewEmployeeDetails(<?= $empleado['id_empleado'] ?>)"
                                    class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                                    title="Ver detalles">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button onclick="confirmStatusChange(<?= $empleado['id_empleado'] ?>, '<?= $empleado['estado'] === 'Activo' ? 'Inactivo' : 'Activo' ?>')"
                                    class="p-2 <?= $empleado['estado'] === 'Activo' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' ?> rounded-lg transition-colors duration-200"
                                    title="<?= $empleado['estado'] === 'Activo' ? 'Desactivar' : 'Activar' ?>">
                                    <i class="fas <?= $empleado['estado'] === 'Activo' ? 'fa-toggle-off' : 'fa-toggle-on' ?>"></i>
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

<!-- Modal de Edición de Empleados -->
<div id="editEmployeeModal">
    <div id="editEmployeeModalContent">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Editar Empleado</h2>
            <button onclick="closeEditEmployeeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editEmployeeForm" class="space-y-4">
            <input type="hidden" id="edit_id_empleado" name="id_empleado">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre*</label>
                    <input type="text" id="edit_empleado_primer_nombre" name="primer_nombre" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                    <input type="text" id="edit_empleado_segundo_nombre" name="segundo_nombre"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido*</label>
                    <input type="text" id="edit_empleado_primer_apellido" name="primer_apellido" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                    <input type="text" id="edit_empleado_segundo_apellido" name="segundo_apellido"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cédula*</label>
                    <input type="text" id="edit_empleado_cedula" name="cedula" required readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado*</label>
                    <select id="edit_empleado_estado" name="estado" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol*</label>
                    <select id="edit_empleado_rol" name="rol" required onchange="toggleEspecialidadEdit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione un rol</option>
                        <option value="Dentista">Dentista</option>
                        <option value="Asistente">Asistente</option>
                        <option value="Administrativo">Administrativo</option>
                    </select>
                </div>
                <div id="edit_especialidad_container" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad*</label>
                    <select id="edit_empleado_especialidad" name="especialidad"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione especialidad</option>
                        <option value="Dentista general">Dentista general</option>
                        <option value="Odontopediatra o dentista pediátrico">Odontopediatra</option>
                        <option value="Ortodoncista">Ortodoncista</option>
                        <option value="Periodoncista o especialista en encías">Periodoncista</option>
                        <option value="Endodoncista o especialista en tratamientos de conducto">Endodoncista</option>
                        <option value="Patólogo oral o cirujano oral">Patólogo oral</option>
                        <option value="Prostodoncista">Prostodoncista</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono*</label>
                    <input type="text" id="edit_empleado_telefono" name="telefono" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico*</label>
                    <input type="email" id="edit_empleado_correo" name="correo" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeEditEmployeeModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
$(document).ready(function() {
    // Inicializar DataTables
    $('#empleadosTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        paging: true,
        searching: true,
        ordering: true,
        info: true
    });
});

// Aquí va el resto de tu código JavaScript...
// (funciones openEditEmployeeModal, viewEmployeeDetails, confirmStatusChange, etc.)
</script>

<script>
// Función para abrir el modal de edición
function openEditEmployeeModal(id) {
    const row = $(`tr[data-id="${id}"]`);
    $('#edit_id_empleado').val(id);
    $('#edit_empleado_primer_nombre').val(row.attr('data-primer-nombre'));
    $('#edit_empleado_segundo_nombre').val(row.attr('data-segundo-nombre'));
    $('#edit_empleado_primer_apellido').val(row.attr('data-primer-apellido'));
    $('#edit_empleado_segundo_apellido').val(row.attr('data-segundo-apellido'));
    $('#edit_empleado_cedula').val(row.attr('data-cedula'));
    $('#edit_empleado_telefono').val(row.attr('data-telefono'));
    $('#edit_empleado_correo').val(row.attr('data-correo'));
    $('#edit_empleado_estado').val(row.attr('data-estado'));

    const rol = row.attr('data-rol');
    $('#edit_empleado_rol').val(rol);
    toggleEspecialidadEdit();
    if (rol === 'Dentista') {
        $('#edit_empleado_especialidad').val(row.attr('data-especialidad'));
    }

    $('#editEmployeeModal').addClass('show');
    $('body').css('overflow', 'hidden');
}

function closeEditEmployeeModal() {
    $('#editEmployeeModal').removeClass('show');
    $('body').css('overflow', 'auto');
}

function toggleEspecialidadEdit() {
    const rol = $('#edit_empleado_rol').val();
    const container = $('#edit_especialidad_container');
    if (rol === 'Dentista') {
        container.show();
        $('#edit_empleado_especialidad').prop('required', true);
    } else {
        container.hide();
        $('#edit_empleado_especialidad').prop('required', false);
    }
}

$('#editEmployeeForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '../../components/empleados/editar_empleado.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Éxito',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'error',
                    confirmButtonColor: '#2563eb'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud',
                icon: 'error',
                confirmButtonColor: '#2563eb'
            });
            console.error('Error:', xhr.responseText);
        }
    });
});

function viewEmployeeDetails(id) {
    const row = $(`tr[data-id="${id}"]`);
    Swal.fire({
        title: 'Detalles del Empleado',
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
                        <p class="font-medium">Rol:</p>
                        <p>${row.attr('data-rol')}</p>
                    </div>
                    <div>
                        <p class="font-medium">Especialidad:</p>
                        <p>${row.attr('data-especialidad') || 'No aplica'}</p>
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
                        <p class="font-medium">Estado:</p>
                        <span class="${row.attr('data-estado') === 'Activo' ? 'status-active' : 'status-inactive'}">
                            ${row.attr('data-estado')}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium">Antigüedad:</p>
                        <p>${row.find('td:nth-child(2) div.text-xs').text().replace('Antigüedad: ', '')}</p>
                    </div>
                </div>
            </div>
        `,
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#2563eb',
        width: '700px'
    });
}

async function confirmStatusChange(id, nuevoEstado) {
    const { value: confirm } = await Swal.fire({
        title: '¿Confirmar cambio de estado?',
        text: `El empleado será marcado como ${nuevoEstado}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, cambiar a ${nuevoEstado}`,
        cancelButtonText: 'Cancelar'
    });
    if (confirm) {
        try {
            const response = await fetch('../../components/empleados/desactivar_empleado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&estado=${nuevoEstado}`
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();
            if (result.success) {
                await Swal.fire({
                    title: 'Estado actualizado',
                    text: result.message,
                    icon: 'success',
                    confirmButtonColor: '#2563eb'
                });
                location.reload();
            } else {
                throw new Error(result.message || 'Error desconocido');
            }
        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#2563eb'
            });
            console.error('Error al cambiar estado:', error);
        }
    }
}
</script>
</body>
</html>