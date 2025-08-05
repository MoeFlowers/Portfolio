<?php
require_once '../../components/database/database.php';

$meses = isset($_GET['meses']) ? intval($_GET['meses']) : 6;

// Consulta SQL
$sql = "
    SELECT DATE_FORMAT(fecha_hora, '%Y-%m') AS mes, COUNT(*) AS cantidad
    FROM citas
    WHERE fecha_hora >= DATE_SUB(NOW(), INTERVAL $meses MONTH)
    GROUP BY mes
    ORDER BY mes ASC";

$result = $conn->query($sql);

$labels = [];
$values = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['mes'];
    $values[] = $row['cantidad'];
}

echo json_encode([
    'labels' => $labels,
    'values' => $values
]);
?>