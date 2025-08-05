<?php
session_start();
require_once '../database/database.php';

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: ../../views/login.php');
    exit;
}

// Validar y limpiar entradas
$usuario = trim($_POST['usuario'] ?? '');
$contraseña = trim($_POST['contraseña'] ?? '');

// Validación básica
if (empty($usuario) || empty($contraseña)) {
    $_SESSION['error'] = 'Por favor, complete todos los campos';
    header('Location: ../../views/login.php');
    exit;
}

try {
    $conn = getDBConnection();
    
    // Consulta preparada para seguridad
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND status = 'Activo'");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Usuario o contraseña incorrectos');
    }

    $user = $result->fetch_assoc();
    
    // Verificar contraseña (md5 es inseguro, esto es solo para ejemplo)
    if (md5($contraseña) !== $user['contraseña']) {
        throw new Exception('Usuario o contraseña incorrectos');
    }

    // Establecer datos de sesión
    $_SESSION['id_usuario'] = $user['id_usuario'];
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
    $_SESSION['ultimo_acceso'] = time();

    // Manejo según tipo de usuario
    switch ($user['tipo_usuario']) {
        case 'Empleado':
            $stmtEmp = $conn->prepare("SELECT id_empleado, primer_nombre, primer_apellido, rol FROM empleados WHERE id_usuario = ?");
            $stmtEmp->bind_param("i", $user['id_usuario']);
            $stmtEmp->execute();
            $empleado = $stmtEmp->get_result()->fetch_assoc();
            
            if ($empleado) {
                $_SESSION['empleado'] = $empleado;
            }
            $_SESSION['success'] = 'Inicio de sesión exitoso';
            header('Location: ../../views/dashboard/dashboard.php');
            break;
            
        case 'Paciente':
            $stmtPac = $conn->prepare("SELECT id_paciente, primer_nombre, primer_apellido FROM pacientes WHERE id_usuario = ?");
            $stmtPac->bind_param("i", $user['id_usuario']);
            $stmtPac->execute();
            $paciente = $stmtPac->get_result()->fetch_assoc();
            
            if ($paciente) {
                $_SESSION['paciente'] = $paciente;
            }
            $_SESSION['success'] = 'Inicio de sesión exitoso';
            header('Location: ../../views/login.php');
            break;
            
        default:
            throw new Exception('Tipo de usuario no reconocido');
    }
    
    exit;

} catch (Exception $e) {
    // Registrar error
    error_log('Error en login: ' . $e->getMessage());
    
    // Redirigir con mensaje de error
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../../views/login.php');
    exit;
}