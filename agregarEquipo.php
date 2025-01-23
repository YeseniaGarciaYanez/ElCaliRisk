<?php
ob_start(); // Iniciar el buffer de salida para evitar salidas no deseadas

require_once 'config.php';

header("Content-Type: application/json"); // Asegurar que la respuesta es JSON

$response = ['success' => false, 'message' => '', 'debug' => []];

function addDebug($message) {
    global $response;
    $response['debug'][] = $message;
}

// Validación de campos requeridos
$required_fields = ['nombre', 'marca', 'categoria', 'descripcion'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        $response['message'] = "Campo requerido faltante: $field";
        ob_clean(); // Limpiar salida previa
        echo json_encode($response);
        exit;
    }
}

// Sanitizar inputs
$nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
$marca = filter_var(trim($_POST['marca']), FILTER_SANITIZE_STRING);
$categoria = filter_var(trim($_POST['categoria']), FILTER_SANITIZE_STRING);
$descripcion = filter_var(trim($_POST['descripcion']), FILTER_SANITIZE_STRING);

try {
    $conn->begin_transaction();

    // Verificar duplicado
    addDebug("Verificando duplicados antes de la inserción");
    $stmt_check = $conn->prepare("SELECT codigoEqp FROM equipo WHERE nombre = ? AND categoria = ?");
    $stmt_check->bind_param("ss", $nombre, $categoria);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    if ($row_check) {
        $response['success'] = true;
        $response['codigo'] = $row_check['codigoEqp'];
        ob_clean(); // Limpiar salida previa
        echo json_encode($response);
        $conn->rollback();
        exit;
    }

    // Insertar el equipo si no existe
    addDebug("Iniciando inserción de equipo");
    $stmt_equipo = $conn->prepare("INSERT INTO equipo (nombre, marca, categoria, descripcion) VALUES (?, ?, ?, ?)");
    $stmt_equipo->bind_param("ssss", $nombre, $marca, $categoria, $descripcion);

    if (!$stmt_equipo->execute()) {
        throw new Exception("Error al insertar el equipo: " . $stmt_equipo->error);
    }

    // Obtener el código generado
    addDebug("Obteniendo el código generado para el equipo");
    $stmt_codigo = $conn->prepare("SELECT codigoEqp FROM equipo WHERE nombre = ? AND categoria = ? ORDER BY codigoEqp DESC LIMIT 1");
    $stmt_codigo->bind_param("ss", $nombre, $categoria);
    $stmt_codigo->execute();
    $result_codigo = $stmt_codigo->get_result();
    $row_codigo = $result_codigo->fetch_assoc();

    if (!$row_codigo) {
        throw new Exception("No se pudo obtener el código generado para el equipo.");
    }

    // Obtener el nombre de la categoría
    addDebug("Obteniendo el nombre de la categoría");
    $stmt_categoria = $conn->prepare("SELECT nombre FROM categoria WHERE codigo = ?");
    $stmt_categoria->bind_param("s", $categoria);
    $stmt_categoria->execute();
    $result_categoria = $stmt_categoria->get_result();
    $row_categoria = $result_categoria->fetch_assoc();

    if (!$row_categoria) {
        throw new Exception("No se pudo obtener el nombre de la categoría.");
    }

    $conn->commit();

    // Respuesta exitosa
    $response = [
        'success' => true,
        'message' => 'Equipo agregado con éxito.',
        'codigo' => $row_codigo['codigoEqp'],
        'categoriaNombre' => $row_categoria['nombre'],  // Añadir el nombre de la categoría
        'debug' => $response['debug'] // Incluir mensajes de depuración
    ];

} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = "Error: " . $e->getMessage();
    addDebug("Error completo: " . $e->getMessage());
} finally {
    if (isset($stmt_equipo)) $stmt_equipo->close();
    if (isset($stmt_codigo)) $stmt_codigo->close();
    if (isset($stmt_check)) $stmt_check->close();
    if (isset($stmt_categoria)) $stmt_categoria->close();
    $conn->close();
}

// Limpiar salida y enviar respuesta JSON
ob_clean();
echo json_encode($response);
exit;
