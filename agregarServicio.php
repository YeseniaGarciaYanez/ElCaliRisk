<?php
include 'config.php';
session_start(); // Iniciar la sesión

header('Content-Type: application/json'); // Asegúrate de devolver una respuesta JSON

if (!isset($_SESSION['codigoAdmin'])) {
    echo json_encode(["success" => false, "message" => "El administrador no está autenticado."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoServicio = $_POST['codigoServicio'];
    $nombreServicio = $_POST['nombreServicio'];
    $descripcionServicio = $_POST['descripcionServicio'];
    $codigoAdmin = $_SESSION['codigoAdmin']; // Obtener el código del administrador desde la sesión

    // Verificar que todos los datos estén presentes
    if (empty($codigoServicio) || empty($nombreServicio) || empty($descripcionServicio)) {
        echo json_encode(["success" => false, "message" => "Datos del formulario incompletos."]);
        exit;
    }

    // Insertar el servicio en la base de datos
    $stmt = $conn->prepare("INSERT INTO servicio (codigo, nombre, descripcion, administrador) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $codigoServicio, $nombreServicio, $descripcionServicio, $codigoAdmin);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Service added successfully.",
            "data" => [
                "codigo" => $codigoServicio,
                "nombre" => $nombreServicio,
                "descripcion" => $descripcionServicio
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>