<?php

include 'config.php';

// Consulta para obtener la cantidad de equipos por categoría
$sql = "SELECT c.nombre AS category, COUNT(e.codigoEqp) AS equipment
        FROM equipo AS e
        INNER JOIN categoria c ON e.categoria = c.codigo
        GROUP BY c.nombre";

$result = $conn->query($sql);

// Preparar los datos para la gráfica
$categories = [];
$equipment_count = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row["category"];
        $equipment_count[] = $row["equipment"];
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los resultados en formato JSON
echo json_encode([
    "categories" => $categories,
    "equipment_count" => $equipment_count
]);
?>