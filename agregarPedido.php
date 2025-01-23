<?php
include 'config.php';

// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Asegurarse de que la respuesta es JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que todos los campos necesarios estén presentes
    if (isset($_POST['cliente'], $_POST['servicio'], $_POST['equipo'], $_POST['fechaPedido'], $_POST['fechaEjecucion'], $_POST['estado'])) {
        $cliente = $_POST['cliente'];
        $servicio = $_POST['servicio'];
        $equipo = $_POST['equipo'];
        $fechaPedido = $_POST['fechaPedido'];
        $fechaEjecucion = $_POST['fechaEjecucion'];
        $estado = $_POST['estado'];

        // Escapar los valores para prevenir inyección SQL
        $cliente = $conn->real_escape_string($cliente);
        $servicio = $conn->real_escape_string($servicio);
        $equipo = $conn->real_escape_string($equipo);
        $fechaPedido = $conn->real_escape_string($fechaPedido);
        $fechaEjecucion = $conn->real_escape_string($fechaEjecucion);
        $estado = $conn->real_escape_string($estado);

        // Insertar el pedido en la base de datos
        $sql = "INSERT INTO pedido (cliente, servicio, equipo, fechaPedido, fechaEjecucion, estado) 
                VALUES ('$cliente', '$servicio', '$equipo', '$fechaPedido', '$fechaEjecucion', '$estado')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Faltan datos en el formulario']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
