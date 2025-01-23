<?php
require_once 'config.php';

// Tu código para obtener equipos sigue aquí
function obtenerEquipos($conn) {
    $equipos = [];

    $sql = "SELECT equipo.codigoEqp, equipo.nombre, equipo.marca, equipo.descripcion,
                   categoria.nombre AS categoria
            FROM equipo
            LEFT JOIN categoria ON equipo.categoria = categoria.codigo;";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $equipos[] = $row;
        }
    }

    return $equipos;
}

$equipos = obtenerEquipos($conn);
$conn->close();

// Imprimir el array de equipos

?>
