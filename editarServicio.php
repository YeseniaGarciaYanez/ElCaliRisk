<?php
include 'config.php';

$response = array();  // Arreglo para almacenar la respuesta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoServicio = $_POST['codigoServicio'];
    $nombreServicio = $_POST['nombreServicio'];
    $descripcionServicio = $_POST['descripcionServicio'];

    // Actualizar el servicio en la base de datos
    $sql = "UPDATE servicio SET nombre = ?, descripcion = ? WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombreServicio, $descripcionServicio, $codigoServicio);

    if ($stmt->execute()) {
        // Si la actualización es exitosa
        $response["success"] = true;
        $response["message"] = "Servicio actualizado exitosamente";
        $response["codigo"] = $codigoServicio;
        $response["nombre"] = $nombreServicio;
        $response["descripcion"] = $descripcionServicio;
    } else {
        // Si hay un error
        $response["success"] = false;
        $response["message"] = "Error al actualizar el servicio: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Si no es una solicitud POST
    $response["success"] = false;
    $response["message"] = "Solicitud no válida";
}

$conn->close();

// Devolver la respuesta como JSON
echo json_encode($response);
?>
