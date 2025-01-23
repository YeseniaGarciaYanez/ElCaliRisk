<?php
include 'config.php';

if (isset($_POST['codigoPedido'])) {
    $codigoPedido = $_POST['codigoPedido'];

    $sql = "SELECT * FROM pedido WHERE CodigoPedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigoPedido);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<p><strong>Code:</strong> {$row['CodigoPedido']}</p>";
            echo "<p><strong>Client:</strong> {$row['cliente']}</p>";
            echo "<p><strong>Order date:</strong> {$row['fechaPedido']}</p>";
            echo "<p><strong>Execution date:</strong> {$row['fechaEjecucion']}</p>";
            echo "<p><strong>Equipment:</strong> {$row['equipo']}</p>";
            echo "<p><strong>Service:</strong> {$row['servicio']}</p>";
            echo "<p><strong>Estado:</strong> {$row['estado']}</p>";
        } else {
            echo "<p>No se encontraron detalles para el pedido seleccionado.</p>";
        }
    } else {
        echo "<p>Error al obtener los detalles del pedido.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Código del pedido no proporcionado.</p>";
}
?>
