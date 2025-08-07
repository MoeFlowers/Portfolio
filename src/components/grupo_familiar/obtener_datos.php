<?php


// Conectar a la base de datos
include '../../controllers/conexion.php';

// Obtener personas no asignadas
$personasQuery = "SELECT id_persona, nombre_completo FROM personas_no_asignadas";
$personasResult = $conn->query($personasQuery);
$personas = $personasResult->fetch_all(MYSQLI_ASSOC);

// Obtener familias
$familiasQuery = "SELECT id_familia, nombre_familia FROM familias";
$familiasResult = $conn->query($familiasQuery);
$familias = $familiasResult->fetch_all(MYSQLI_ASSOC);

// Enviar datos como JSON
echo json_encode(['personas' => $personas, 'familias' => $familias]);
?>
