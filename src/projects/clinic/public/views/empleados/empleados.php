<?php
session_start();
require_once '../../components/database/database.php';

// Obtener conexión a la base de datos
$conn = getDBConnection();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Obtener empleados
$empleados = [];
$sql = "SELECT * FROM empleados ORDER BY primer_nombre, primer_apellido";
$result = $conn->query($sql);

if ($result) {
    $empleados = $result->fetch_all(MYSQLI_ASSOC);
}

// Mostrar mensaje si existe
if (isset($_SESSION['mensaje'])) {
    $mensaje = explode("|", $_SESSION['mensaje']);
    $tipo = $mensaje[0];
    $texto = $mensaje[1];

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

    unset($_SESSION['mensaje']);
}

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Gestión de Empleados</title>
    <link rel="icon" href="../../assets/images/logo-removebg-preview-removebg-preview.png" type="image/x-icon">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <link href="../../../src/utils/output.css" rel="stylesheet">
    <script src="../../assets/js/sweetalert2@11.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/jquery.dataTables.min.css">
    <style>
        #addEmployeeModal input,
        #addEmployeeModal textarea,
        #addEmployeeModal select {
            user-select: text !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
        }

        #addEmployeeModal input:focus,
        #addEmployeeModal textarea:focus {
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
            <main class="p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-users-cog mr-2 text-blue-600"></i>
                            Gestión de Empleados
                        </h1>
                        <button onclick="openModal('addEmployeeModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Nuevo Empleado
                        </button>
                    </div>
                    <!-- Tabla de empleados -->
                    <?php include '../../components/empleados/table.php'; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal para agregar empleado -->
    <div id="addEmployeeModal" class="modal" style="display: none; position: fixed; z-index: 10; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 800px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <!-- Encabezado del modal -->
            <div class="modal-header" style="background-color: #2563eb; color: white; padding: 15px 20px; border-radius: 6px 6px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: #1d4ed8; padding: 8px; border-radius: 6px; margin-right: 12px;">
                        <i class="fas fa-user-tie" style="color: white; font-size: 20px;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 600;">Registro de Nuevo Empleado</h3>
                </div>
                <button onclick="closeModal('addEmployeeModal')" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body" style="padding: 20px 0; max-height: 70vh; overflow-y: auto;">
                <form id="employeeForm" method="post" action="../../components/empleados/registrar_empleado.php" onsubmit="return validarFormulario()">
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
                            </div>
                        </div>

                        <!-- Información Profesional -->
                        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                            <h4 style="font-size: 18px; color: #111827; margin-bottom: 15px; display: flex; align-items: center;">
                                <i class="fas fa-briefcase" style="color: #2563eb; margin-right: 8px;"></i>
                                Información Profesional
                            </h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label for="rol" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Rol*</label>
                                    <select id="rol" name="rol" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                        <option value="">Seleccione...</option>
                                        <option value="Dentista">Dentista</option>
                                        <option value="Asistente">Asistente</option>
                                        <option value="Administrativo">Administrativo</option>
                                    </select>
                                    <div id="error-rol" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div id="especialidadContainer" style="display: none;">
                                    <label for="especialidad" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Especialidad*</label>
                                    <select id="especialidad" name="especialidad"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                        <option value="">Seleccione...</option>
                                        <option value="Dentista general">Dentista general</option>
                                        <option value="Odontopediatra o dentista pediátrico">Odontopediatra</option>
                                        <option value="Ortodoncista">Ortodoncista</option>
                                        <option value="Periodoncista o especialista en encías">Periodoncista</option>
                                        <option value="Endodoncista o especialista en tratamientos de conducto">Endodoncista</option>
                                        <option value="Patólogo oral o cirujano oral">Patólogo oral</option>
                                        <option value="Prostodoncista">Prostodoncista</option>
                                    </select>
                                    <div id="error-especialidad" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
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
                                    <label for="correo" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Correo Electrónico*</label>
                                    <input type="email" id="correo" name="correo" required
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Ej: empleado@clinica.com">
                                    <div id="error-correo" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Acceso (solo para Asistentes y Administrativos) -->
                        <div id="accesoContainer" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; display: none;">
                            <h4 style="font-size: 18px; color: #111827; margin-bottom: 15px; display: flex; align-items: center;">
                                <i class="fas fa-key" style="color: #2563eb; margin-right: 8px;"></i>
                                Información de Acceso
                            </h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label for="usuario" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Usuario*</label>
                                    <input type="text" id="usuario" name="usuario"
                                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                        placeholder="Nombre de usuario para el sistema">
                                    <div id="error-usuario" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                                <div>
                                    <label for="contrasena" style="display: block; margin-bottom: 5px; font-size: 14px; color: #374151;">Contraseña*</label>
                                    <div style="position: relative;">
                                        <input type="password" id="contrasena" name="contrasena"
                                            style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                            placeholder="Mínimo 8 caracteres">
                                        <i id="togglePassword" class="fas fa-eye" style="position: absolute; right: 10px; top: 10px; cursor: pointer; color: #6b7280;"></i>
                                    </div>
                                    <div id="error-contrasena" style="color: #dc2626; font-size: 12px; margin-top: 5px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie del modal -->
                    <div style="background-color: #f9fafb; padding: 15px 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between;">
                        <button type="button" onclick="closeModal('addEmployeeModal')"
                            style="display: inline-flex; align-items: center; padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background-color: white; color: #374151; font-size: 14px; cursor: pointer;">
                            <i class="fas fa-times" style="margin-right: 8px;"></i> Cancelar
                        </button>
                        <div style="display: flex; gap: 12px;">
                            <button type="button" onclick="clearEmployeeForm()"
                                style="display: inline-flex; align-items: center; padding: 8px 16px; border: none; border-radius: 6px; background-color: #dbeafe; color: #1e40af; font-size: 14px; cursor: pointer;">
                                <i class="fas fa-eraser" style="margin-right: 8px;"></i> Limpiar
                            </button>
                            <button type="submit"
                                style="display: inline-flex; align-items: center; padding: 8px 16px; border: none; border-radius: 6px; background-color: #2563eb; color: white; font-size: 14px; cursor: pointer;">
                                <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Empleado
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.tailwindcss.min.js"></script>
    <script>
        // Mostrar/ocultar campos según el rol seleccionado
        document.getElementById('rol').addEventListener('change', function() {
            const especialidadContainer = document.getElementById('especialidadContainer');
            const accesoContainer = document.getElementById('accesoContainer');
            const especialidadSelect = document.getElementById('especialidad');
            const usuarioInput = document.getElementById('usuario');
            const contrasenaInput = document.getElementById('contrasena');

            if (this.value === 'Dentista') {
                especialidadContainer.style.display = 'block';
                accesoContainer.style.display = 'none';
                especialidadSelect.required = true;
                usuarioInput.required = false;
                contrasenaInput.required = false;
            } else if (this.value === 'Asistente' || this.value === 'Administrativo') {
                especialidadContainer.style.display = 'none';
                accesoContainer.style.display = 'block';
                especialidadSelect.required = false;
                usuarioInput.required = true;
                contrasenaInput.required = true;
            } else {
                especialidadContainer.style.display = 'none';
                accesoContainer.style.display = 'none';
                especialidadSelect.required = false;
                usuarioInput.required = false;
                contrasenaInput.required = false;
            }
        });

        // Toggle para mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('contrasena');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // === FUNCIONES DEL MODAL ===
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
            clearEmployeeForm();
        }

        function clearEmployeeForm() {
            document.getElementById("employeeForm").reset();
            document.getElementById("especialidadContainer").style.display = "none";
            document.getElementById("accesoContainer").style.display = "none";

            const errors = document.querySelectorAll(".modal-body div[id^='error-']");
            errors.forEach(function(el) {
                el.textContent = "";
            });
        }

        function validarFormulario() {
            let isValid = true;

            // Limpiar mensajes anteriores
            const errors = document.querySelectorAll(".modal-body div[id^='error-']");
            errors.forEach(function(el) {
                el.textContent = "";
            });

            // Validaciones por campo
            const campos = {
                primer_nombre: "Primer Nombre",
                primer_apellido: "Primer Apellido",
                cedula: "Cédula",
                rol: "Rol",
                telefono: "Teléfono",
                correo: "Correo Electrónico"
            };

            for (const campo in campos) {
                const valor = document.getElementById(campo).value.trim();
                if (!valor || valor === "") {
                    document.getElementById("error-" + campo).textContent = "Este campo no puede estar vacío.";
                    isValid = false;
                }
            }

            // Validación especialidad si es dentista
            const rol = document.getElementById('rol').value;
            if (rol === 'Dentista') {
                const especialidad = document.getElementById('especialidad').value.trim();
                if (!especialidad || especialidad === "") {
                    document.getElementById("error-especialidad").textContent = "La especialidad es requerida para dentistas.";
                    isValid = false;
                }
            }

            // Validación usuario y contraseña si es asistente o administrativo
            if (rol === 'Asistente' || rol === 'Administrativo') {
                const usuario = document.getElementById('usuario').value.trim();
                const contrasena = document.getElementById('contrasena').value.trim();
                
                if (!usuario || usuario === "") {
                    document.getElementById("error-usuario").textContent = "El usuario es requerido.";
                    isValid = false;
                }
                
                if (!contrasena || contrasena === "") {
                    document.getElementById("error-contrasena").textContent = "La contraseña es requerida.";
                    isValid = false;
                } else if (contrasena.length < 8) {
                    document.getElementById("error-contrasena").textContent = "La contraseña debe tener al menos 8 caracteres.";
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

            return isValid;
        }

        // Detectar tecla ESC para cerrar modal
        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape") {
                closeModal("addEmployeeModal");
            }
        });
    </script>
</body>

</html>