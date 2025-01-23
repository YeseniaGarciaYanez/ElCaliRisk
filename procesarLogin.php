<?php
// procesarLogin.php
session_start(); // Asegúrate de que la sesión esté iniciada

include 'config.php'; // Archivo de configuración con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que los campos no estén vacíos
    if (!empty($_POST['Username']) && !empty($_POST['password'])) {
        $Username = $_POST['Username'];
        $password = $_POST['password'];
        $userFound = false;

        // Función para verificar el login en una tabla específica
        function verificarLogin($conn, $tabla, $Username, $password, &$codigo, &$nombre) {
            // Ajustar el SELECT para usar los nombres correctos de las columnas
            if ($tabla === 'administrador') {
                $sql = "SELECT codigo, nombrePila, contraseña FROM $tabla WHERE usuario = ?";
            } elseif ($tabla === 'cliente') {
                $sql = "SELECT codigocliente, nombre, contraseña FROM $tabla WHERE usuario = ?"; // Cambiado a codigocliente
            } else {
                $sql = "SELECT codigo, nombre, contraseña FROM $tabla WHERE usuario = ?";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $Username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Asignar resultados a las variables
                $stmt->bind_result($codigo, $nombre, $contraseñaHash);
                $stmt->fetch();
                
                // Comparar la contraseña ingresada con el hash almacenado en la base de datos
                if (password_verify($password, $contraseñaHash)) {
                    return true; // Inicio de sesión exitoso
                }
            }
            $stmt->close();
            return false; // Usuario no encontrado o contraseña incorrecta
        }

        // Verificar en la tabla tecnico
        if (verificarLogin($conn, 'tecnico', $Username, $password, $codigo, $nombre)) {
            $userFound = true;
            $_SESSION['tipo_usuario'] = 'tecnico';
        }
        // Si no es tecnico, verificar en administrador
        elseif (verificarLogin($conn, 'administrador', $Username, $password, $codigo, $nombre)) {
            $userFound = true;
            $_SESSION['tipo_usuario'] = 'administrador';
            $_SESSION['codigoAdmin'] = $codigo; // Establecer el código del administrador en la sesión
        }
        // Si no es administrador, verificar en cliente
        elseif (verificarLogin($conn, 'cliente', $Username, $password, $codigo, $nombre)) {
            $userFound = true;
            $_SESSION['tipo_usuario'] = 'cliente';   // Establecer el tipo de usuario como cliente
            $_SESSION['codigocliente'] = $codigo;    // Establecer el código del cliente en la sesión
        }

        // Si se encontró un usuario válido
        if ($userFound) {
            $_SESSION['codigo'] = $codigo;
            $_SESSION['nombre'] = $nombre;

            // Redirigir según el tipo de usuario
            switch ($_SESSION['tipo_usuario']) {
                case 'tecnico':
                    header("Location: /Tecnico/tecDashboard.php");
                    break;
                case 'administrador':
                    header("Location: adminDashboard.php");
                    break;
                case 'cliente':
                    header("Location: /cliente/clientDashboard.php");
                    break;
            }
            exit; // Asegurarse de que no se ejecute más código después de la redirección
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }

    $conn->close();
}
?>
