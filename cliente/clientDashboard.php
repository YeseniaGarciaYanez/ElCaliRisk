<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header('Location: ../customerPortal.php');
    exit();
}

$nombre = $_SESSION['nombre'];

// Consulta para obtener los pedidos y los equipos asociados
$sql = "SELECT p.CodigoPedido, p.fechaPedido, p.fechaEjecucion, p.estado, s.nombre AS nombre_servicio, e.nombre AS nombre_equipo
        FROM pedido p
        LEFT JOIN servicio s ON p.servicio = s.codigo
        LEFT JOIN equipo e ON p.equipo = e.codigoEqp
        WHERE p.cliente = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['codigocliente']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No orders found for this client.";
} else {
    // Aquí ya puedes ver el resultado, pero vamos a mostrarlo en la tabla más abajo.
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/icon.png" type="image/png">
    <title>CaliRisk - Client Dashboard</title>
    <link rel="stylesheet" href="../css/clientDashboard.css">
</head>
<body>
    <div id="clientDashboard">
        <!-- Encabezado -->
        <header>
    <img src= https://i.imgur.com/WDqrhaM.png alt="Logo" class="header-logo">
        <h1>Welcome, <?php echo $_SESSION['nombre']; ?>!</h1>
    </header>

        <!-- Barra lateral con navegación entre secciones -->
        <nav class="sidebar">
            <a href="#" onclick="mostrarSeccion('solicitarServicio')">Request service</a>
            <a href="#" onclick="mostrarSeccion('misServicios')">My Services</a>
            <a href="logout.php">Log out</a>
        </nav>

        <!-- Sección Solicitar Servicio (visible por defecto) -->
        <section id="solicitarServicio" class="seccion">
            <div class="solicitar-servicio-container">
                <h3>Request Service</h3>
                <form id="formSolicitarServicio" method="POST" class="formulario">
                    <label for="descripcion" class="form-label">Description of the request:</label>
                    <textarea name="descripcion" id="descripcion" rows="4" maxlength="1000" required></textarea>
                    <button type="submit" class="button">Send Request</button>
                </form>
                <p id="mensajeExito" class="mensaje-exito" style="color: green;"></p>
                <p id="mensajeError" class="mensaje-error" style="color: red;"></p>
            </div>
        </section>


        <!-- Sección Mis Servicios (oculta por defecto) -->
        <section id="misServicios" class="seccion" style="display: none;">
            <h2>My Services</h2>
            <p>The requested services will be displayed here.</p>

            <!-- Tabla de Servicios -->
            <table>
                <thead>
                    <tr>
                        <th>Order Code</th>
                        <th>Service Name</th>
                        <th>Equipment Name</th>
                        <th>Order Date</th>
                        <th>Execution Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="serviciosTabla">
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['CodigoPedido']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_servicio']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_equipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['fechaPedido']); ?></td>
                                <td><?php echo htmlspecialchars($row['fechaEjecucion']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">No orders found for this client.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        // Función para mostrar la sección solicitada y ocultar las demás
        function mostrarSeccion(idSeccion) {
            const secciones = document.querySelectorAll('.seccion');
            secciones.forEach(section => section.style.display = 'none');  // Oculta todas las secciones
            document.getElementById(idSeccion).style.display = 'block';     // Muestra la sección seleccionada
        }
    </script>
<script>
    // Función para manejar la solicitud AJAX
    document.getElementById("formSolicitarServicio").addEventListener("submit", function(event) {
        event.preventDefault();  // Evitar que el formulario se envíe de la forma convencional

        var descripcion = document.getElementById("descripcion").value;
        var formData = new FormData();
        formData.append("descripcion", descripcion);

        // Realizar la solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "solicitudcliente.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);  // Convertir la respuesta JSON a un objeto

                // Mostrar el mensaje según la respuesta
                if (response.status === 'success') {
                    document.getElementById("mensajeExito").innerText = response.message;
                    document.getElementById("mensajeError").innerText = '';  // Limpiar el mensaje de error
                    // Limpiar el campo de descripción después de enviar la solicitud con éxito
                    document.getElementById("descripcion").value = '';  // Borra el contenido del textarea
                } else {
                    document.getElementById("mensajeError").innerText = response.message;
                    document.getElementById("mensajeExito").innerText = '';  // Limpiar el mensaje de éxito
                }
            }
        };
        xhr.send(formData);
    });
</script>


    <footer>
        <p>&copy; 2024 CaliRisk. All rights reserved.</p>
    </footer>
</body>
</html>

<?php $stmt->close(); ?>
