<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['password']);

    // Validar campos vacíos
    if (empty($usuario) || empty($contrasena)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, complete todos los campos.']);
        exit();
    }

    // Convertir contraseña a MD5
    $contrasena_md5 = md5($contrasena);

    // Verificar credenciales
    $sql = "SELECT id_usuario, contraseña FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($user_id, $stored_password_md5); // Asume que la BD guarda MD5
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        if ($contrasena_md5 === $stored_password_md5) { // Comparación directa
            $_SESSION['usuario'] = $usuario;
            $_SESSION['id_usuario'] = $user_id;

            // Bitácora
            $accion = 'Inicio de sesión';
            $descripcion = "El usuario $usuario ha iniciado sesión.";
            $sql_bitacora = "INSERT INTO bitacora (id_usuario, accion, descripcion) VALUES (?, ?, ?)";
            $stmt_bitacora = $conn->prepare($sql_bitacora);
            $stmt_bitacora->bind_param("iss", $user_id, $accion, $descripcion);
            $stmt_bitacora->execute();
            $stmt_bitacora->close();

            echo json_encode(['status' => 'success', 'message' => 'Ingreso exitoso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    }

    $conn->close();
    exit();
}
?>