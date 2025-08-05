<?php
session_start();
require_once '../../components/database/database.php'; // Asegúrate que esta ruta es correcta

// Obtener conexión a la base de datos
$conn = getDBConnection(); // Esta línea faltaba

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Obtener pacientes
$pacientes = [];
$sql = "SELECT * FROM pacientes ORDER BY primer_nombre, primer_apellido";
$result = $conn->query($sql);

if ($result) {
    $pacientes = $result->fetch_all(MYSQLI_ASSOC);
}

// Mostrar mensaje si existe
if (isset($_SESSION['mensaje'])) {
    $mensaje = explode("|", $_SESSION['mensaje']);
    $tipo = $mensaje[0]; // "success" o "error"
    $texto = $mensaje[1]; // El mensaje en sí

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$tipo',
                title: '$tipo',
                text: '$texto',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>";

    // Eliminar el mensaje después de mostrarlo
    unset($_SESSION['mensaje']);
}

// Cerrar conexión (opcional, se cierra automáticamente al final del script)
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Gestión de Pacientes</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <script src="../../assets/js/sweetalert2@11.js"></script>
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <style>
        /* Permite selección de texto en todos los inputs */
        #addPatientModal input,
        #addPatientModal textarea,
        #addPatientModal select {
            user-select: text !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
        }

        /* Auto-selección al enfocar */
        #addPatientModal input:focus,
        #addPatientModal textarea:focus {
            user-select: auto !important;
        }
    </style>
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
                        <h1 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-users mr-2 text-blue-600"></i>
                            Gestión de Pacientes
                        </h1>
                        <button onclick="openModal('addPatientModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Nuevo Paciente
                        </button>
                    </div>
                    <!-- Tabla de pacientes -->
                    <?php include '../../components/pacientes/table.php'; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal para agregar paciente -->
    <div id="addPatientModal" class="modal" style="display: none; position: fixed; z-index: 10; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 800px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <!-- Encabezado del modal -->
            <div class="modal-header" style="background-color: #2563eb; color: white; padding: 15px 20px; border-radius: 6px 6px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: #1d4ed8; padding: 8px; border-radius: 6px; margin-right: 12px;">
                        <i class="fas fa-user-plus" style="color: white; font-size: 20px;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 600;">Registro de Nuevo Paciente</h3>
                </div>
                <button onclick="closeModal('addPatientModal')" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body" style="padding: 20px 0; max-height: 70vh; overflow-y: auto;">
                <form id="patientForm" method="post" action="../../components/pacientes/registrar_paciente.php" onsubmit="return validarFormulario()">
                    <div style="margin-bottom: 30px;">
                        <!-- Información Personal -->
                        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                            <h4 style="font-size: 18px; color: #111827; margin-bottom: 15px; display: flex; align-items: center;">
                                <i class="fas fa-id-card" style="color: #2563eb; margin-right: 8px;"></i>
                                Información Personal
                            </h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label for="primer_nombre" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Primer Nombre*</label>
                                    <input type="text" id="primer_nombre" name="primer_nombre" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <div id="error-primer_nombre" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div>
                                    <label for="segundo_nombre" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Segundo Nombre</label>
                                    <input type="text" id="segundo_nombre" name="segundo_nombre"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label for="primer_apellido" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Primer Apellido*</label>
                                    <input type="text" id="primer_apellido" name="primer_apellido" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <div id="error-primer_apellido" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div>
                                    <label for="segundo_apellido" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Segundo Apellido</label>
                                    <input type="text" id="segundo_apellido" name="segundo_apellido"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label for="cedula" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Cédula*</label>
                                    <input type="text" id="cedula" name="cedula" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: 1234567890">
                                    <div id="error-cedula" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div>
                                    <label for="fecha_nacimiento" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Fecha de Nacimiento*</label>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <div id="error-fecha_nacimiento" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div>
                                    <label for="genero" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Género*</label>
                                    <select id="genero" name="genero" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                        <option value="">Seleccione...</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    <div id="error-genero" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
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
                                    <label for="telefono" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Teléfono*</label>
                                    <input type="tel" id="telefono" name="telefono" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: 3101234567">
                                    <div id="error-telefono" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div>
                                    <label for="correo" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Correo Electrónico</label>
                                    <input type="email" id="correo" name="correo"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: paciente@example.com">
                                    <div id="error-correo" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div style="grid-column: span 2;">
                                    <label for="direccion" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Dirección</label>
                                    <input type="text" id="direccion" name="direccion"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: Calle 123 #45-67">
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
                                <label for="alergias" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Alergias</label>
                                <textarea id="alergias" name="alergias" rows="2"
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                    placeholder="Ej: Penicilina, mariscos, etc."></textarea>
                            </div>
                            <div style="margin-top: 15px;">
                                <label for="tipo_sangre" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Tipo de Sangre*</label>
                                <select id="tipo_sangre" name="tipo_sangre" required
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
                                <div id="error-tipo_sangre" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie del modal -->
                    <div style="background-color: #f9fafb; padding: 15px 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between;">
                        <button type="button" onclick="closeModal('addPatientModal')"
                            style="display: inline-flex; align-items: center; padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background-color: white; color: #374151; font-size: 14px; cursor: pointer;">
                            <i class="fas fa-times" style="margin-right: 8px;"></i> Cancelar
                        </button>
                        <div style="display: flex; gap: 12px;">
                            <button type="button" onclick="clearPatientForm()"
                                style="display: inline-flex; align-items: center; padding: 8px 16px; border: none; border-radius: 6px; background-color: #dbeafe; color: #1e40af; font-size: 14px; cursor: pointer;">
                                <i class="fas fa-eraser" style="margin-right: 8px;"></i> Limpiar
                            </button>
                            <button type="submit"
                                style="display: inline-flex; align-items: center; padding: 8px 16px; border: none; border-radius: 6px; background-color: #2563eb; color: white; font-size: 14px; cursor: pointer;">
                                <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Paciente
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // === FUNCIONES DEL MODAL ===

        // Abrir modal
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        // Cerrar modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
            clearPatientForm(); // Limpiar campos al cerrar
        }

        // Limpiar formulario
        function clearPatientForm() {
            document.getElementById("patientForm").reset();
            // Limpiar mensajes de error
            const errors = document.querySelectorAll(".modal-body div[id^='error-']");
            errors.forEach(function(el) {
                el.textContent = "";
            });
        }

        // Validación del formulario antes de enviar
        function validarFormulario() {
            let isValid = true;

            // Limpiar mensajes anteriores
            const errors = document.querySelectorAll(".modal-body div[id^='error-']");
            errors.forEach(function(el) {
                el.textContent = "";
            });


            const campos = {
                primer_nombre: "Primer Nombre",
                primer_apellido: "Primer Apellido",
                cedula: "Cédula",
                fecha_nacimiento: "Fecha de Nacimiento",
                genero: "Género",
                telefono: "Teléfono",
                tipo_sangre: "Tipo de Sangre" // Nuevo campo requerido
            };

            for (const campo in campos) {
                const valor = document.getElementById(campo).value.trim();
                if (!valor || valor === "") {
                    document.getElementById("error-" + campo).textContent = "Este campo no puede estar vacío.";
                    isValid = false;
                }
            }

            // Validación de cédula (solo números y longitud mínima de 7)
            const cedula = document.getElementById("cedula").value.trim();
            if (cedula && !/^\d{7,}$/.test(cedula)) {
                document.getElementById("error-cedula").textContent = "La cédula debe tener al menos 7 dígitos.";
                isValid = false;
            }

            // Validación de teléfono (mínimo 10 dígitos)
            const telefono = document.getElementById("telefono").value.trim();
            if (telefono && !/^\d{10,}$/.test(telefono)) {
                document.getElementById("error-telefono").textContent = "El teléfono debe tener al menos 10 dígitos.";
                isValid = false;
            }

            // Validación de correo (formato básico)
            const correo = document.getElementById("correo").value.trim();
            if (correo && !/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(correo)) {
                document.getElementById("error-correo").textContent = "Correo electrónico inválido.";
                isValid = false;
            }

            return isValid; // Si es false, no se envía el formulario
        }




        // Detectar tecla ESC para cerrar modal
        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape") {
                closeModal("addPatientModal");
            }
        });
    </script>
    <script src="../../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.tailwindcss.min.js"></script>
</body>

</html>