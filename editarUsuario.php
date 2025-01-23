<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $tipoUsuario = $_POST['tipoUsuario'];

    // Crear la consulta SQL para actualizar según el tipo de usuario
    if ($tipoUsuario === 'administrador') {
        $sql = "UPDATE administrador SET nombrePila = ?, contraseña = ? WHERE usuario = ?";
    } elseif ($tipoUsuario === 'cliente') {
        $sql = "UPDATE cliente SET nombre = ?, contraseña = ? WHERE usuario = ?";
    } elseif ($tipoUsuario === 'tecnico') {
        $sql = "UPDATE tecnico SET nombre = ?, contraseña = ? WHERE usuario = ?";
    } else {
        die("Tipo de usuario no válido.");
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombreUsuario, $password, $usuario);

    if ($stmt->execute()) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirigir al dashboard
    header('Location: adminDashboard.php');
    exit();
}
?>
