<?php
include 'config.php';

if (isset($_POST['usuario']) && isset($_POST['tipo'])) {
    $usuario = $_POST['usuario'];
    $tipo = $_POST['tipo'];

    // Determinar la tabla según el tipo de usuario
    switch ($tipo) {
        case 'administrador':
            $tabla = 'administrador';
            break;
        case 'cliente':
            $tabla = 'cliente';
            break;
        case 'tecnico':
            $tabla = 'tecnico';
            break;
        default:
            echo "Tipo de usuario no válido.";
            exit;
    }

    // Eliminar el usuario de la tabla correspondiente
    $sql = "DELETE FROM $tabla WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);

    if ($stmt->execute()) {
        echo "Usuario eliminado exitosamente.";
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Datos incompletos.";
}
?>
