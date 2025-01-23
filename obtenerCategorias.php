<?php
require_once 'config.php';

$sql = "SELECT codigo, nombre FROM categoria"; // Ajusta esta consulta según tu base de datos
$result = $conn->query($sql);

$categorias = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Devolver las categorías en formato JSON
header('Content-Type: application/json');
echo json_encode(['success' => true, 'data' => $categorias]);

$conn->close();
?>
