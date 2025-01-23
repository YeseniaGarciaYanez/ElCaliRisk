<?php

include 'config.php';
// Obtener el mes desde la URL o definir uno por defecto
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m'); // Si no se pasa el mes, se toma el mes actual

// Consulta SQL para contar los pedidos del mes especificado
$sql = "SELECT COUNT(p.CodigoPedido) AS pedido
        FROM pedido AS p
        WHERE MONTH(p.fechaEjecucion) = ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mes); // Vincula el mes a la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Devolver el resultado en formato JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>