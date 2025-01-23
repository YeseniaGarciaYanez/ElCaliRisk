<?php
session_start();
include '../config.php';  // Archivo de configuración de la base de datos

// Verifica si la sesión del cliente está activa
if (!isset($_SESSION['codigocliente'])) {
    echo json_encode(['status' => 'error', 'message' => 'Error: User not authenticated.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['descripcion']) && !empty($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
        $codigocliente = $_SESSION['codigocliente'];  // Obtener el código del cliente desde la sesión

        // Insertar en la tabla 'solicitudes'
        $sql = "INSERT INTO solicitudes (descripcion) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $descripcion);
        $stmt->execute();
        $numero_solicitud = $stmt->insert_id;  // Obtener el número de solicitud insertado
        $stmt->close();

        // Insertar en la tabla 'solicitud_cliente'
        $sql = "INSERT INTO solicitud_cliente (solicitud, cliente) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $numero_solicitud, $codigocliente);  // "i" para INT y "s" para VARCHAR
        $stmt->execute();
        $stmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Request sent correctly.']);  // Respuesta en formato JSON
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please complete the description of the application.']);
    }
}
?>

