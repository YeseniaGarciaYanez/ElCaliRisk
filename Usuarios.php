<?php
header("Content-Type: application/json"); // Devolver respuesta como JSON

include 'config.php';

// Inicializar la respuesta por defecto
$response = ["status" => "error", "message" => "Hubo un error, vuelva a intentarlo."];

try {
    // Validar datos comunes
    $usuario = $_POST['usuario'] ?? null;
    $password = $_POST['password'] ?? null;
    $tipoUsuario = $_POST['tipoUsuario'] ?? null;

    if (!$usuario || !$password || !$tipoUsuario) {
        throw new Exception("Todos los campos obligatorios deben completarse.");
    }

    // Encriptar la contraseña antes de almacenarla
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "";
    $params = [];
    $paramTypes = "";

    // Configurar consulta según el tipo de usuario
    if ($tipoUsuario === "administrator") {
        $nombrePila = $_POST['nombrePila'] ?? null;
        $primerApellido = $_POST['primerApellido'] ?? null;
        $segundoApellido = $_POST['segundoApellido'] ?? null;

        $sql = "INSERT INTO administrador (nombrePila, usuario, contraseña, primerApellido, segundoApellido) 
                VALUES (?, ?, ?, ?, ?)";
        $params = [$nombrePila, $usuario, $passwordHash, $primerApellido, $segundoApellido];
        $paramTypes = "sssss";

    } elseif ($tipoUsuario === "client") {
        $nombreCliente = $_POST['nombreCliente'] ?? null;
        $contacto = $_POST['contacto'] ?? null;
        $numerotelCliente = $_POST['numerotelCliente'] ?? null;
        $correoElecCliente = $_POST['correoElecCliente'] ?? null;

        $sql = "INSERT INTO cliente (usuario, contraseña, nombre, contacto, numerotel, correoElec) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$usuario, $passwordHash, $nombreCliente, $contacto, $numerotelCliente, $correoElecCliente];
        $paramTypes = "ssssss";

    } elseif ($tipoUsuario === "technician") {
        $nombreTecnico = $_POST['nombreTecnico'] ?? null;
        $primerApell = $_POST['primerApell'] ?? null;
        $segundoApell = $_POST['segundoApell'] ?? null;
        $numTel = $_POST['numTel'] ?? null;
        $area_especialidad = $_POST['area_especialidad'] ?? null;

        $sql = "INSERT INTO tecnico (nombre, usuario, contraseña, primerApell, segundoApell, numTel, area_especialidad) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [$nombreTecnico, $usuario, $passwordHash, $primerApell, $segundoApell, $numTel, $area_especialidad];
        $paramTypes = "sssssss";

    } else {
        throw new Exception("Tipo de usuario no válido.");
    }

    // Ejecutar la consulta
    if ($sql) {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta.");
        }

        $stmt->bind_param($paramTypes, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $response = ["status" => "success", "message" => "User added correctly."];
        $stmt->close();
    }
} catch (Exception $e) {
    // Registrar el error en el log del servidor
    error_log($e->getMessage());
    $response["message"] = $e->getMessage(); // Opcional: para depuración
}

// Cerrar conexión
$conn->close();

// Enviar respuesta JSON al cliente
echo json_encode($response);
?>
