<?php
// actualizarEstadoPedido.php

include 'config.php';  // Asegúrate de que la conexión a la base de datos esté incluida

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoPedido = isset($_POST['codigoPedido']) ? $_POST['codigoPedido'] : '';
    $estadoPedido = isset($_POST['estadoPedido']) ? $_POST['estadoPedido'] : '';

    // Validar que los datos estén presentes
    if (!empty($codigoPedido) && !empty($estadoPedido)) {
        // Escapar valores para evitar inyecciones SQL
        $codigoPedido = $conn->real_escape_string($codigoPedido);
        $estadoPedido = $conn->real_escape_string($estadoPedido);

        // Actualizar estado del pedido en la base de datos
        $sql = "UPDATE pedido SET estado = '$estadoPedido' WHERE CodigoPedido = '$codigoPedido'";

        if ($conn->query($sql) === TRUE) {
            // Si la actualización es exitosa, responder con éxito
            echo json_encode(['success' => true, 'mensaje' => 'Status Updated Successfully.']);
        } else {
            // Si hubo un error en la consulta, responder con un mensaje de error
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar el estado']);
        }
    } else {
        // Si los datos no son válidos, responder con un error
        echo json_encode(['success' => false, 'mensaje' => 'Datos inválidos']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Método de solicitud no válido']);
}
$conn->close();
?>
