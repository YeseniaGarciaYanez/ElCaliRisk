<?php
include 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Leer los datos JSON desde el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Asegurarse de que el código del servicio está presente
$codigoServicio = $data['codigoServicio'] ?? '';

if (empty($codigoServicio)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Código de servicio es obligatorio"]);
    exit;
}

// Iniciar una transacción
$conn->begin_transaction();

try {
    // Lista de tablas con relaciones al servicio
    $tablas_relacionadas = [
        'cliente_servicio',
        'pedido_servicio',
        'servicio_equipo'
    ];

    // Eliminar registros relacionados en todas las tablas
    foreach ($tablas_relacionadas as $tabla) {
        $sql = "DELETE FROM $tabla WHERE servicio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $codigoServicio);
        $stmt->execute();
        $stmt->close();
    }

    // Eliminar el servicio de la tabla `servicio`
    $sqlEliminarServicio = "DELETE FROM servicio WHERE codigo = ?";
    $stmtEliminarServicio = $conn->prepare($sqlEliminarServicio);
    $stmtEliminarServicio->bind_param("s", $codigoServicio);
    $stmtEliminarServicio->execute();
    $servicioEliminado = $stmtEliminarServicio->affected_rows;
    $stmtEliminarServicio->close();

    // Confirmar la transacción
    $conn->commit();

    // Establecer el código de estado a 204 (No Content) para indicar éxito sin devolver contenido
    if ($servicioEliminado > 0) {
        http_response_code(204); // Servicio eliminado correctamente
    } else {
        http_response_code(404); // No se encontró el servicio
        echo json_encode(["error" => "Servicio no encontrado"]);
    }
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error al eliminar el servicio", "mensaje" => $e->getMessage()]);
}

$conn->close();
?>
