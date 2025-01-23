<?php
include 'config.php';

// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar conexión a la base de datos
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
    exit;
}

// Consulta para obtener los pedidos
$sql = "SELECT CodigoPedido, cliente, fechaPedido, estado FROM pedido";
$result = $conn->query($sql);

$pedidos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
}

// Devolver datos en formato JSON
header('Content-Type: application/json');
echo json_encode(['pedidos' => $pedidos]);
exit;
