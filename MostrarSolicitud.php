<?php
session_start();
include 'config.php'; // Conexión a la base de datos

// Configurar el encabezado para devolver JSON
header('Content-Type: application/json');

try {
    // Consulta SQL
    $sql = "SELECT sc.solicitud, sc.cliente 
            FROM solicitud_cliente sc
            INNER JOIN solicitudes s ON sc.solicitud = s.numero_solicitud
            INNER JOIN cliente c ON sc.cliente = c.codigocliente";

    $result = $conn->query($sql);

    $datos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
    }

    echo json_encode($datos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

// Asegúrate de que no haya salida adicional
exit();
?>
