<?php
require_once 'config.php'; // Asegúrate de que esta línea esté presente

header('Content-Type: application/json'); // Asegúrate de establecer el tipo de contenido

$data = json_decode(file_get_contents('php://input'), true);
$codigo = $data['codigoEquipo'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit;
}

// Imprimir para depuración
file_put_contents('debug.txt', print_r($data, true)); // Guardar lo recibido en debug.txt

if (isset($data['codigo'])) {
    $codigo = $data['codigo'];
    $query = "DELETE FROM equipo WHERE codigoEqp = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        echo json_encode(['mensaje' => 'Error en la preparación de la consulta SQL.']);
        exit;
    }

    $stmt->bind_param('s', $codigo);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Equipo eliminado correctamente.']);
    } else {
        $error = $stmt->error; // Captura el error
        echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar el equipo en la base de datos: ' . $error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Código de equipo no proporcionado.']);
}

?>