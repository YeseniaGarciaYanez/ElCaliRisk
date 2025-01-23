<?php

include 'config.php';
// Consulta para obtener la cantidad de técnicos por área
$sql = "SELECT ae.nombre AS Area, COUNT(t.codigo) AS technician
        FROM tecnico AS t
        INNER JOIN area_especialidad ae ON t.area_especialidad = ae.codigo
        GROUP BY ae.nombre";

$result = $conn->query($sql);

// Preparar los datos para la gráfica
$areas = [];
$technician_count = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $areas[] = $row["Area"];
        $technician_count[] = $row["technician"];
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los resultados en formato JSON
echo json_encode([
    "areas" => $areas,
    "technician_count" => $technician_count
]);
?>