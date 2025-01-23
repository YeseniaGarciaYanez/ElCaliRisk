<?php
// Configuración de la conexión
include 'config.php';

try {
    // Seleccionar todas las contraseñas no encriptadas
    $sqlSelect = "SELECT usuario, contraseña FROM tecnico";
    $result = $conn->query($sqlSelect);

    if ($result->num_rows > 0) {
        // Preparar la consulta para actualizar contraseñas
        $sqlUpdate = "UPDATE tecnico SET contraseña = ? WHERE usuario = ?";
        $stmt = $conn->prepare($sqlUpdate);

        while ($row = $result->fetch_assoc()) {
            $usuario = $row['usuario'];
            $passwordActual = $row['contraseña'];

            // Encriptar la contraseña existente
            $passwordHash = password_hash($passwordActual, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $stmt->bind_param("ss", $passwordHash, $usuario);
            $stmt->execute();

            // Opcional: Mostrar progreso (para depuración)
            echo "Usuario: $usuario - Contraseña encriptada actualizada<br>";
        }

        echo "Se han encriptado todas las contraseñas correctamente.";
        $stmt->close();
    } else {
        echo "No se encontraron usuarios con contraseñas.";
    }
} catch (Exception $e) {
    echo "Ocurrió un error: " . $e->getMessage();
}

// Cerrar la conexión
$conn->close();
?>
