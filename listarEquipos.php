<?php
header('Content-Type: application/json');
require_once 'config.php';

$response = ['success' => false, 'message' => '', 'data' => []];

try {
    $sql = "SELECT equipo.codigoEqp, equipo.nombre, equipo.descripcion, equipo.marca, categoria.nombre AS categoria
            FROM equipo
            LEFT JOIN categoria ON equipo.categoria = categoria.codigo";
        
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
        $response['success'] = true;
    } else {
        $response['message'] = 'No se encontraron equipos.';
    }
} catch (Exception $e) {
    $response['message'] = 'Error al obtener los equipos: ' . $e->getMessage();
}

echo json_encode($response);
?>