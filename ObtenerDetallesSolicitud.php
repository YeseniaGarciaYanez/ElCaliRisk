<?php
// obtenerDetallesSolicitud.php
include 'config.php'; // Incluye el archivo de configuración de la base de datos

header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

// Inicializamos la variable $datos como un array vacío
$datos = [];

if (isset($_GET['numero'])) {  // Usamos GET en lugar de POST para seguir la convención de URLs
    $numeroSolicitud = $_GET['numero'];  // Obtenemos el número de solicitud desde la URL

    // Consulta para obtener los detalles de la solicitud
    $sql = "SELECT descripcion FROM solicitudes WHERE numero_solicitud = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numeroSolicitud);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($descripcion);

    // Verificar si se encontró la solicitud
    if ($stmt->num_rows > 0) {
        $stmt->fetch();  // Obtener la descripción de la solicitud

        // Llenar el array $datos con la información de la solicitud
        $datos = [
            "numero_solicitud" => $numeroSolicitud,
            "descripcion" => $descripcion
        ];
    } else {
        // Si no se encuentra la solicitud, devolver un error en formato JSON
        $datos = ["error" => "No details found for this request."];
    }

    $stmt->close();
    $conn->close();
} else {
    // Si no se proporciona un número de solicitud, devolver un error en formato JSON
    $datos = ["error" => "No request number provided."];
}

// Devolver los datos como JSON
echo json_encode($datos);
?>


