<?php
include '../../controllers/conexion.php'; // Ajusta la ruta según tu estructura

$response = [];

// Obtener manzanas
$sqlManzanas = "SELECT id_manzana, nro_manzana FROM manzanas";
$resultManzanas = $conn->query($sqlManzanas);
$response['manzanas'] = $resultManzanas->fetch_all(MYSQLI_ASSOC);

// Obtener casas filtradas por la manzana seleccionada
if (isset($_GET['id_manzana'])) {
    $idManzana = $_GET['id_manzana'];
    $sqlCasas = "SELECT id_casa, nro_casa FROM casas WHERE id_manzana = ?";
    $stmtCasas = $conn->prepare($sqlCasas);
    $stmtCasas->bind_param("i", $idManzana);
    $stmtCasas->execute();
    $resultCasas = $stmtCasas->get_result();
    $response['casas'] = $resultCasas->fetch_all(MYSQLI_ASSOC);
} else {
    // Si no se ha seleccionado una manzana, obtener todas las casas
    $sqlCasas = "SELECT id_casa, nro_casa FROM casas";
    $resultCasas = $conn->query($sqlCasas);
    $response['casas'] = $resultCasas->fetch_all(MYSQLI_ASSOC);
}

// Obtener grupos familiares filtrados por la casa seleccionada
if (isset($_GET['id_casa'])) {
    $idCasa = $_GET['id_casa'];
    $sqlGruposFamiliares = "SELECT id_persona, nombre, nombre_familia FROM datos_casas WHERE id_casa = ?";
    $stmtGruposFamiliares = $conn->prepare($sqlGruposFamiliares);
    $stmtGruposFamiliares->bind_param("i", $idCasa);
    $stmtGruposFamiliares->execute();
    $resultGruposFamiliares = $stmtGruposFamiliares->get_result();
    $response['grupos_familiares'] = $resultGruposFamiliares->fetch_all(MYSQLI_ASSOC);
} else {
    // Si no se ha seleccionado una casa, devolver un array vacío
    $response['grupos_familiares'] = [];
}

// Obtener beneficios
$sqlBeneficios = "SELECT id_beneficio, nombre_beneficio FROM beneficios";
$resultBeneficios = $conn->query($sqlBeneficios);
$response['beneficios'] = $resultBeneficios->fetch_all(MYSQLI_ASSOC);

echo json_encode($response);

$conn->close();
?>
