<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensaje'] = "error|Debe iniciar sesión para registrar empleados";
    header("Location: ../login.php");
    exit;
}

require_once '../../components/database/database.php';
$conn = getDBConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $primer_nombre = trim($_POST['primer_nombre'] ?? '');
    $segundo_nombre = trim($_POST['segundo_nombre'] ?? '');
    $primer_apellido = trim($_POST['primer_apellido'] ?? '');
    $segundo_apellido = trim($_POST['segundo_apellido'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $rol = trim($_POST['rol'] ?? '');
    $especialidad = ($rol === 'Dentista') ? trim($_POST['especialidad'] ?? '') : null;
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $fecha_registro = date('Y-m-d H:i:s');
    $estado = 'Activo';
    $id_usuario = null;

    // Validar campos obligatorios
    $camposRequeridos = [
        'primer_nombre' => 'Primer nombre',
        'primer_apellido' => 'Primer apellido',
        'cedula' => 'Cédula',
        'rol' => 'Rol',
        'telefono' => 'Teléfono',
        'correo' => 'Correo electrónico'
    ];

    foreach ($camposRequeridos as $campo => $nombre) {
        if (empty($$campo)) {
            $_SESSION['mensaje'] = "error|El campo $nombre es obligatorio";
            header("Location: ../../views/empleados/empleados.php");
            exit;
        }
    }

    // Validaciones para roles administrativos/asistentes
    if ($rol === 'Administrativo' || $rol === 'Asistente') {
        if (empty($usuario)) {
            $_SESSION['mensaje'] = "error|El nombre de usuario es obligatorio para este rol";
            header("Location: ../../views/empleados/empleados.php");
            exit;
        }

        if (empty($contrasena)) {
            $_SESSION['mensaje'] = "error|La contraseña es obligatoria para este rol";
            header("Location: ../../views/empleados/empleados.php");
            exit;
        }

        if (strlen($contrasena) < 8) {
            $_SESSION['mensaje'] = "error|La contraseña debe tener al menos 8 caracteres";
            header("Location: ../../views/empleados/empleados.php");
            exit;
        }
    }

    // Verificar si la cédula ya existe
    $sql_verificar = "SELECT id_empleado FROM empleados WHERE cedula = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $cedula);
    $stmt_verificar->execute();
    
    if ($stmt_verificar->get_result()->num_rows > 0) {
        $_SESSION['mensaje'] = "error|La cédula ya está registrada";
        $stmt_verificar->close();
        header("Location: ../../views/empleados/empleados.php");
        exit;
    }
    $stmt_verificar->close();

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Registrar usuario si es necesario
        if ($rol === 'Administrativo' || $rol === 'Asistente') {
            // Verificar si el usuario existe
            $sql_check_user = "SELECT id_usuario FROM usuarios WHERE usuario = ?";
            $stmt_check = $conn->prepare($sql_check_user);
            $stmt_check->bind_param("s", $usuario);
            $stmt_check->execute();
            
            if ($stmt_check->get_result()->num_rows > 0) {
                $_SESSION['mensaje'] = "error|El nombre de usuario ya está en uso";
                throw new Exception("Usuario ya existe");
            }
            $stmt_check->close();

            // Insertar usuario con MD5
            $hashed_password = md5($contrasena); // Usando MD5 para el hash
            $tipo_usuario = 'Empleado';
            
            $sql_usuario = "INSERT INTO usuarios (usuario, tipo_usuario, contraseña, status) VALUES (?, ?, ?, 'Activo')";
            $stmt_usuario = $conn->prepare($sql_usuario);
            $stmt_usuario->bind_param("sss", $usuario, $tipo_usuario, $hashed_password);
            
            if (!$stmt_usuario->execute()) {
                throw new Exception("Error al registrar usuario: " . $stmt_usuario->error);
            }
            
            $id_usuario = $stmt_usuario->insert_id;
            $stmt_usuario->close();
        }

        // Insertar empleado
        $sql_empleado = "INSERT INTO empleados (
            id_usuario, cedula, primer_nombre, segundo_nombre, 
            primer_apellido, segundo_apellido, especialidad, rol, 
            telefono, correo, fecha_registro, estado
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_empleado = $conn->prepare($sql_empleado);
        $stmt_empleado->bind_param(
            "isssssssssss",
            $id_usuario, $cedula, $primer_nombre, $segundo_nombre,
            $primer_apellido, $segundo_apellido, $especialidad, $rol,
            $telefono, $correo, $fecha_registro, $estado
        );

        if (!$stmt_empleado->execute()) {
            throw new Exception("Error al registrar empleado: " . $stmt_empleado->error);
        }

        // Confirmar la transacción
        $conn->commit();
        $_SESSION['mensaje'] = "success|Empleado registrado correctamente";

    } catch (Exception $e) {
        // Revertir en caso de error
        $conn->rollback();
        $_SESSION['mensaje'] = "error|Error al registrar: " . $e->getMessage();
    }

    $stmt_empleado->close();
} else {
    $_SESSION['mensaje'] = "error|Método no permitido";
}

$conn->close();
header("Location: ../../views/empleados/empleados.php");
exit;
?>