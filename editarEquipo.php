<?php
require_once 'config.php';

// Mostrar errores para ayudar en la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar respuesta
$response = ['success' => false, 'message' => ''];

// Verificar si se recibieron los datos esperados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoEqp = $_POST['codigo'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    $marca = $_POST['marca'] ?? null;
    $estado = $_POST['estado'] ?? null; // Asegúrate de que esto se envíe correctamente

    // Verificar que todos los datos estén presentes
    if ($codigoEqp && $nombre && $descripcion && $categoria && $marca) {
        try {
            // Comprobar si el equipo existe
            $stmt_check = $conn->prepare("SELECT COUNT(*) as count FROM equipo WHERE codigoEqp = ?");
            $stmt_check->bind_param("s", $codigoEqp);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] == 0) {
                throw new Exception('El equipo no existe.');
            }

            // Actualizar la información del equipo en la tabla 'equipo'
            $sqlEquipo = "UPDATE equipo 
                          SET nombre = ?, descripcion = ?, marca = ?, categoria = ?, estado = ? 
                          WHERE codigoEqp = ?";
            
            $stmt = $conn->prepare($sqlEquipo);
            $stmt->bind_param("ssssss", $nombre, $descripcion, $marca, $categoria, $estado, $codigoEqp);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Equipo actualizado con éxito.';
            } else {
                throw new Exception('Error al actualizar el equipo: ' . $conn->error);
            }

        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    } else {
        $response['message'] = 'Faltan datos para actualizar el equipo.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>