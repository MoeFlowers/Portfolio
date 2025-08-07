<?php
$servername = "localhost"; 
$username = "root";          
$password = "";              
$database = "servicio_comunitario_final";      
             

// Crear conexión usando mysqli
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
}

// Si la conexión es exitosa, puedes continuar con tu lógica aquí

?>
