<?php
session_start();
include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = md5($_POST['password']);
    $sql = "SELECT id_usuario, contraseña FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    $stmt->close();
    if ($user_id) {
        if ($contrasena == $hashed_password) {
            $_SESSION['usuario'] = $usuario;
            $response = ['status' => 'success', 'message' => 'Ingreso exitoso'];
            header("Location: ../pages/dashboard.php");
        } else {
            $response = ['status' => 'error', 'message' => 'Contraseña incorrecta'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Usuario no encontrado'];
    }
    echo json_encode($response);
}
?>