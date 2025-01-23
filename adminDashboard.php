<?php
// Incluir configuración de la base de datos y obtener los equipos
require_once 'config.php';
$equipos = include 'obtenerEquipos.php';
?>

<?php
session_start();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Si no ha iniciado sesión o no es administrador, redirigir al formulario de inicio de sesión
    header('Location: ../customerPortal.php');
    exit();
}

// Obtener el nombre del usuario
$nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/icon.png" type="image/png">
    <link rel="stylesheet" href="./css/adminDashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   

    

    <title>Admin Dashboard - CaliRisk</title>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="#gestion-usuarios">User Managment</a></li>
            <li><a href="#administracion-servicios">Services Managment</a></li>
            <li><a href="#gestion-pedidos">Order Managment</a></li>
            <li><a href="#control-equipos">Equipment Control</a></li>
            <li><a href="#certificados">Certificates and Documentation</a></li>
            <li><a href="#alertas">Alerts and Notifications</a></li>
            <li><a href="#request">Request</a></li>
            <li><a href="#stadistics">Stadistics</a></li>
            <a href="logout.php">Log out</a>
        </ul>
    </div>

    <header>
    <img src= https://i.imgur.com/WDqrhaM.png alt="Logo" class="header-logo">
        <h1>Welcome, <?php echo $_SESSION['nombre']; ?>!</h1>
    </header>

    <div class="main-content">

<!-- GESTION DE USUARIOS -->
<section id="gestion-usuarios" class="gestion-usuarios">
    <h2>Gestión de Usuarios</h2>

     <!-- Formulario para agregar un usuario (administrador, cliente, técnico) -->
     <form id="formAgregarUsuario" method="POST" action="Usuarios.php" class="formulario">
    <h3>Add User</h3>

    <!-- Campos comunes -->
    <label for="usuario">User:</label>
    <input type="text" id="usuario" name="usuario" class="input-texto" required pattern="[A-Za-z0-9]+" title="Only letters and numbers are allowed.">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" class="input-texto" required  pattern="[A-Za-z0-9]+" title="Only letters and numbers are allowed.">

     <!-- Selector para elegir el tipo de usuario -->
    
    <label for="tipoUsuario">Type of user:</label>
    <select id="tipoUsuario" name="tipoUsuario" class="input-text" required>
        <option value="">Select an option:</option>
        <option value="administrator">Administrator</option>
        <option value="client">Client</option>
        <option value="technician">Technician</option>
    </select>

    <!-- Client-specific fields -->
    <div id="camposCliente" style="display:none;">
        <label for="nombreCliente">Name:</label>
        <input type="text" id="nombreCliente" name="nombreCliente" class="input-text" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed.">

        <label for="contacto">Contact:</label>
        <input type="text" id="contacto" name="contacto" class="input-text" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed.">

        <label for="numerotelCliente">Phone number:</label>
        <input type="text" id="numerotelCliente" name="numerotelCliente" class="input-text" pattern="\d+" inputmode="numeric" title="Only numbers allowed.">

        <label for="correoElecCliente">Email:</label>
        <input type="email" id="correoElecCliente" name="correoElecCliente" class="input-text">
    </div>

    <!-- Technician-specific fields -->
    <div id="camposTecnico" style="display:none;">
        <label for="nombreTecnico">Name:</label>
        <input type="text" id="nombreTecnico" name="nombreTecnico" class="input-text" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed.">

        <label for="primerApell">First surname:</label>
        <input type="text" id="primerApell" name="primerApell" class="input-text" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed.">

        <label for="segundoApell">Second surname:</label>
        <input type="text" id="segundoApell" name="segundoApell" class="input-text" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed.">

        <label for="numTel">Phone number:</label>
        <input type="text" id="numTel" name="numTel" class="input-text" pattern="\d+" inputmode="numeric" title="Only numbers allowed.">

        <label for="area_especialidad">Specialty area:</label>
        <input type="text" id="area_especialidad" name="area_especialidad" class="input-text">
    </div>

    <!-- Administrator-specific fields -->
    <div id="camposAdministrador" style="display:none;">
        <label for="nombrePila">Name:</label>
        <input type="text" id="nombrePila" name="nombrePila" class="input-text" pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces are allowed." >

        <label for="primerApellido">First surname:</label>
        <input type="text" id="primerApellido" name="primerApellido" class="input-text" pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces are allowed." >

        <label for="segundoApellido">Second surname:</label>
        <input type="text" id="segundoApellido" name="segundoApellido" class="input-text" pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces are allowed.">
    </div>

    <button type="submit" class="button">Add user</button>
</form>


<div id="responseMessage" style="display:none;"></div>

<script>
   $(document).ready(function() {
    // Manejamos el envío del formulario de manera asíncrona
    $("#formAgregarUsuario").on("submit", function(e) {
        e.preventDefault(); // Prevenir el envío tradicional del formulario

        var formData = $(this).serialize(); // Serializamos los datos del formulario

        $.ajax({
            url: $(this).attr('action'), // Enviar al archivo especificado en el atributo action del formulario (Usuarios.php)
            type: "POST",
            data: formData, // Enviar los datos del formulario
            dataType: "json", // Esperar una respuesta JSON
            success: function(response) {
                // Mostrar el mensaje en la misma página
                let messageBox = $("<div>").addClass("response-message");
                if (response.status === "success") {
                    messageBox.addClass("success").text(response.message);
                } else {
                    messageBox.addClass("error").text(response.message || "Hubo un error.");
                }
                $(".response-message").remove(); // Eliminar cualquier mensaje anterior
                $("#formAgregarUsuario").after(messageBox); // Mostrar el mensaje debajo del formulario
                
                // Retrasar la recarga de la página por 3 segundos
                setTimeout(function() {
                    location.reload(); // Recargar la página después de 3 segundos
                }, 3000); // 3000 milisegundos = 3 segundos
            },
            
            error: function() {
                // Manejo de errores si la solicitud AJAX falla
                let errorBox = $("<div>").addClass("response-message error").text("Ocurrió un error inesperado.");
                $(".response-message").remove();
                $("#formAgregarUsuario").after(errorBox);
            }
        });
    });
});

</script>

<script>
document.getElementById('tipoUsuario').addEventListener('change', function() {
    const tipoUsuario = this.value;

    // Hide all fields
    document.getElementById('camposCliente').style.display = 'none';
    document.getElementById('camposTecnico').style.display = 'none';
    document.getElementById('camposAdministrador').style.display = 'none';

    // Remove 'required' attribute from all fields
    removeRequiredAttributes();

    // Show relevant fields based on the selected user type
    if (tipoUsuario == 'client') {
        document.getElementById('camposCliente').style.display = 'block';
        addRequiredAttributes('nombreCliente', 'contacto', 'numerotelCliente', 'correoElecCliente');
    } else if (tipoUsuario == 'technician') {
        document.getElementById('camposTecnico').style.display = 'block';
        addRequiredAttributes('nombreTecnico', 'primerApell', 'numTel', 'area_especialidad');
    } else if (tipoUsuario == 'administrator') {
        document.getElementById('camposAdministrador').style.display = 'block';
        addRequiredAttributes('nombrePila', 'primerApellido', 'segundoApellido');
    }
});

// Function to add 'required' attribute only to visible and enabled fields
function addRequiredAttributes(...fields) {
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input && input.style.display !== 'none' && !input.disabled) {  // Only adds 'required' if the field is visible and enabled
            input.setAttribute('required', 'false');
        }
    });
}

// Function to remove 'required' attribute from hidden fields
function removeRequiredAttributes() {
    const allFields = ['nombreCliente', 'contacto', 'numerotelCliente', 'correoElecCliente', 
                        'nombreTecnico', 'primerApell', 'numTel', 'area_especialidad', 
                        'nombrePila', 'primerApellido', 'segundoApellido'];

    allFields.forEach(field => {
        const input = document.getElementById(field);
        if (input && (input.style.display === 'none' || input.disabled)) {  // Removes 'required' if the field is hidden or disabled
            input.removeAttribute('required');
        }
    });
}


</script>


    <!-- Barra de búsqueda -->
<div class="barra-busqueda">
    <input type="text" id="buscarUsuario" placeholder="Search by name, user or rol...">
</div>

    <?php
include 'config.php';

// Consultas separadas para cada tipo de usuario
$sql_admin = "SELECT nombrePila, usuario, 'administrador' AS tipo FROM administrador";
$sql_cliente = "SELECT nombre, usuario, 'cliente' AS tipo FROM cliente";
$sql_tecnico = "SELECT nombre, usuario, 'tecnico' AS tipo FROM tecnico";

// Obtener resultados de administradores
$result_admin = $conn->query($sql_admin);
if ($result_admin->num_rows > 0) {
    echo "<h3>Admins</h3><table class='tabla-usuarios'>";
    echo "<thead><tr><th>Name</th><th>User</th><th>Type of user</th><th>Actions</th></tr></thead><tbody>";
    while ($row = $result_admin->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nombrePila']}</td>
                <td>{$row['usuario']}</td>
                <td>{$row['tipo']}</td>
                <td>
                    <button class='boton-accion editar' data-usuario='{$row['usuario']}' data-tipo='{$row['tipo']}'>Edit</button>
                    <button class='boton-accion eliminar' data-usuario='{$row['usuario']}' data-tipo='{$row['tipo']}'>Delete</button>
                </td>
            </tr>";
    }
    echo "</tbody></table>";
}

// Obtener resultados de clientes
$result_cliente = $conn->query($sql_cliente);
if ($result_cliente->num_rows > 0) {
    echo "<h3>Customers</h3><table class='tabla-usuarios'>";
    echo "<thead><tr><th>Name</th><th>User</th><th>Type of user</th><th>Actions</th></tr></thead><tbody>";
    while ($row = $result_cliente->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['usuario']}</td>
                <td>{$row['tipo']}</td>
                <td>
                    <button class='boton-accion editar' data-usuario='{$row['usuario']}' data-tipo='{$row['tipo']}'>Edit</button>
                    <button class='boton-accion eliminar' data-usuario='{$row['usuario']}' data-tipo='{$row['tipo']}'>Delete</button>
                </td>
            </tr>";
    }
    echo "</tbody></table>";
    
}

// Obtener resultados de técnicos
$result_tecnico = $conn->query($sql_tecnico);
if ($result_tecnico->num_rows > 0) {
    echo "<h3>Technicians</h3><table class='tabla-usuarios'>";
    echo "<thead><tr><th>Name</th><th>User</th><th>Type of user</th><th>Actions</th></tr></thead><tbody>";
    while ($row = $result_tecnico->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['usuario']}</td>
                <td>{$row['tipo']}</td>
                <td>
                    <button class='boton-accion editar' data-usuario='{$row['usuario']}' data-tipo='{$row['tipo']}'>Edit</button>
                    <button class='boton-accion eliminar' data-usuario='{$row['usuario']}' data-tipo='{$row['tipo']}'>Delete</button>
                </td>
            </tr>";
    }
    echo "</tbody></table>";
    
}

// Cerrar la conexión después de realizar todas las consultas
$conn->close();
?>

    </tbody>
</table>

<script>
    // Función para eliminar un usuario
    document.querySelectorAll('.eliminar').forEach(boton => {
        boton.addEventListener('click', function () {
            const usuario = this.getAttribute('data-usuario');
            const tipo = this.getAttribute('data-tipo');

            if (confirm(`¿Are you sure to delete:  ${usuario}?`)) {
                // Enviar solicitud AJAX para eliminar al usuario
                const formData = new FormData();
                formData.append('usuario', usuario);
                formData.append('tipo', tipo);

                fetch('eliminarUsuario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Recargar la página para reflejar los cambios
                })
                .catch(error => console.error('Error deleting the user:', error));
            }
        });
    });
</script>

   <!-- Modal para editar un usuario -->
   <div id="modalEditarUsuario" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit user</h2>
            <form id="formEditarUsuario" method="POST" action="editarUsuario.php">
                <input type="hidden" id="editarTipoUsuario" name="tipoUsuario">
                <label for="editarNombreUsuario">Name:</label>
                <input type="text" id="editarNombreUsuario" name="nombreUsuario" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces are allowed.">
                <label for="editarUsuario">User:</label>
                <input type="text" id="editarUsuario" name="usuario" readonly>
                <label for="editarPassword">Password:</label>
                <input type="password" id="editarPassword" name="password"  pattern="[A-Za-z0-9]+" title="Only letters and numbers are allowed.">
                <button type="submit">Save changes</button>
            </form>
        </div>
    </div>

    <script>
        // Función para eliminar un usuario
        document.querySelectorAll(".eliminar").forEach(button => {
            button.addEventListener("click", function () {
                const usuario = this.getAttribute("data-usuario");
                const tipoUsuario = this.closest("tr").querySelector("td:nth-child(3)").innerText.toLowerCase();

                if (confirm("Are you sure you want to delete this user?")) {
                    // Redirigir a eliminarUsuario.php con el usuario y tipoUsuario como parámetros GET
                    window.location.href = `eliminarUsuario.php?usuario=${usuario}&tipoUsuario=${tipoUsuario}`;
                }
            });
        });
    </script>

<script>
    // Validación de datos para los formularios
    function validarFormularioAdministrador() {
        const nombre = document.getElementById("nombrePila").value.trim();
        const usuario = document.getElementById("usuarioAdmin").value.trim();
        const password = document.getElementById("passwordAdmin").value;
        const primerApellido = document.getElementById("primerApellido").value.trim();
        const segundoApellido = document.getElementById("segundoApellido").value.trim();

        if (!nombre || !usuario || !password || !primerApellido) {
            alert("Todos los campos son obligatorios, excepto el segundo apellido.");
            return false;
        }

        if (password.length < 6) {
            alert("The password must be at least 6 characters long.");
            return false;
        }

        return true;
    }

    function validarFormularioCliente() {
        const nombre = document.getElementById("nombreCliente").value.trim();
        const usuario = document.getElementById("usuarioCliente").value.trim();
        const password = document.getElementById("passwordCliente").value;
        const contacto = document.getElementById("contacto").value.trim();
        const telefono = document.getElementById("numerotelCliente").value.trim();
        const correo = document.getElementById("correoElecCliente").value.trim();

        if (!nombre || !usuario || !password || !contacto || !telefono || !correo) {
            alert("All fields are required.");
            return false;
        }

        if (!/^\d{10}$/.test(telefono)) {
            alert("The telephone number must be exactly 10 digits long.");
            return false;
        }

        if (!/\S+@\S+\.\S+/.test(correo)) {
            alert("The telephone number must be exactly 10 digits long.");
            return false;
        }

        if (password.length < 6) {
            alert("The password must be at least 6 characters long.");
            return false;
        }

        return true;
    }

    function validarFormularioTecnico() {
        const nombre = document.getElementById("nombreTecnico").value.trim();
        const usuario = document.getElementById("usuarioTecnico").value.trim();
        const password = document.getElementById("passwordTecnico").value;
        const primerApellido = document.getElementById("primerApell").value.trim();
        const segundoApellido = document.getElementById("segundoApell").value.trim();
        const telefono = document.getElementById("numTel").value.trim();
        const areaEspecialidad = document.getElementById("area_especialidad").value.trim();

        if (!nombre || !usuario || !password || !primerApellido || !telefono || !areaEspecialidad) {
            alert("All fields are required, except the second last name.");
            return false;
        }

        if (!/^\d{10}$/.test(telefono)) {
            alert("The telephone number must be exactly 10 digits long.");
            return false;
        }

        if (!/^[A-Z]{2}\d{1}$/.test(areaEspecialidad)) {
            alert("The area of specialty should follow the format of two letters and a number (e.g., AE1).");
            return false;
        }

        if (password.length < 6) {
            alert("The password must be at least 6 characters long.");
            return false;
        }

        return true;
    }

    // Asignar las funciones de validación a los formularios
    document.getElementById("formAgregarAdministrador").onsubmit = validarFormularioAdministrador;
    document.getElementById("formAgregarCliente").onsubmit = validarFormularioCliente;
    document.getElementById("formAgregarTecnico").onsubmit = validarFormularioTecnico;
</script>

<script>
        // Obtener elementos del DOM
        const modalEditar = document.getElementById("modalEditarUsuario");
        const closeBtn = document.querySelector(".close");

        // Función para abrir el modal y rellenar los campos
        document.querySelectorAll(".editar").forEach(button => {
            button.addEventListener("click", function () {
                const usuario = this.getAttribute("data-usuario");
                const fila = this.closest("tr");
                const nombre = fila.querySelector("td:nth-child(1)").innerText;
                const tipoUsuario = fila.querySelector("td:nth-child(3)").innerText.toLowerCase();

                // Rellenar el formulario del modal
                document.getElementById("editarNombreUsuario").value = nombre;
                document.getElementById("editarUsuario").value = usuario;
                document.getElementById("editarTipoUsuario").value = tipoUsuario;

                // Mostrar el modal
                modalEditar.style.display = "block";
            });
        });

        // Función para cerrar el modal
        closeBtn.addEventListener("click", function () {
            modalEditar.style.display = "none";
        });

        // Cerrar el modal si se hace clic fuera del contenido
        window.addEventListener("click", function (event) {
            if (event.target === modalEditar) {
                modalEditar.style.display = "none";
            }
        });
    </script>



<script>
    // Función para filtrar los usuarios en la tabla
    document.getElementById('buscarUsuario').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.tabla-usuarios tbody tr');

        rows.forEach(row => {
            const nombre = row.cells[0].textContent.toLowerCase();
            const usuario = row.cells[1].textContent.toLowerCase();
            const tipo = row.cells[2].textContent.toLowerCase();

            // Mostrar la fila si coincide con la búsqueda
            if (nombre.includes(filter) || usuario.includes(filter) || tipo.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

</section>



<section id="administracion-servicios">
    <h2>Service Management</h2>
    <p>Manage services offered.</p>

    <!-- Formulario para agregar un nuevo servicio -->
    <form id="formAgregarServicio" method="POST" action="agregarServicio.php">
        <h3>Add Service</h3>
        <label for="codigoServicio">Service Code:</label>
        <input type="text" id="codigoServicio" name="codigoServicio" required>
        <label for="nombreServicio">Name of the Service:</label>
        <input type="text" id="nombreServicio" name="nombreServicio" required  pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">
        <label for="descripcionServicio">Description:</label>
        <input type="text" id="descripcionServicio" name="descripcionServicio" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">
        <button type="submit">Add Service</button>
    </form>

    <!-- Mensaje de resultado -->
    <div id="resultado"></div>

    <script>
    document.getElementById("formAgregarServicio").addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch("agregarServicio.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => {
        const resultado = document.getElementById("resultado");

        if (data.success) {
            resultado.textContent = data.message;

            // Limpiar formulario
            document.getElementById("formAgregarServicio").reset();

            // Agregar el nuevo servicio a la tabla
            const tablaServicios = document.getElementById("tablaServicios").querySelector("tbody");
            const nuevaFila = document.createElement("tr");
            nuevaFila.setAttribute("data-codigo", data.data.codigo); // Para identificar la fila
            nuevaFila.innerHTML = `
                <td>${data.data.codigo}</td>
                <td>${data.data.nombre}</td>
                <td>${data.data.descripcion}</td>
                <td>
                    <button class='editar' data-codigo='${data.data.codigo}' data-nombre='${data.data.nombre}' data-descripcion='${data.data.descripcion}'>Edit</button>
                    <button class='eliminar' data-codigo='${data.data.codigo}'>Delete</button>
                </td>
            `;
            tablaServicios.appendChild(nuevaFila);

            // Registrar eventos en los nuevos botones
            nuevaFila.querySelector(".editar").addEventListener("click", function() {
                const codigo = this.getAttribute("data-codigo");
                const nombre = this.getAttribute("data-nombre");
                const descripcion = this.getAttribute("data-descripcion");
                cargarDatosServicio(codigo, nombre, descripcion);
            });

            nuevaFila.querySelector(".eliminar").addEventListener("click", function() {
                const codigo = this.getAttribute("data-codigo");
                eliminarServicio(codigo);
            });
        } else {
            resultado.textContent = "Error: " + data.message;
        }
    })
    .catch(error => {
        document.getElementById("resultado").textContent = "Ocurrió un error: Duplictae service Code";
    });
});
</script>

    <!-- Tabla de servicios existentes -->
    <h3>Existing Services</h3>
    <div class="search-container">
        <input type="text" id="buscarServicio" placeholder="Search by code or name...">
    </div>

    <table id="tablaServicios">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'config.php';
            $sql = "SELECT * FROM servicio";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr data-codigo='{$row['codigo']}'>
                        <td>{$row['codigo']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>
                            <button class='editar' data-codigo='{$row['codigo']}' data-nombre='{$row['nombre']}' data-descripcion='{$row['descripcion']}'>Edit</button>
                            <button class='eliminar' data-codigo='{$row['codigo']}'>Delete</button>
                        </td>
                      </tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <!-- Modal para editar un servicio -->
    <div class="modal" id="modalEditarServicio">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3>Edit service</h3>
            <form id="formEditarServicio" method="POST">
                <input type="hidden" id="codigoServicioEdit" name="codigoServicio">
                <label for="nombreServicioEdit">Service name:</label>
                <input type="text" id="nombreServicioEdit" name="nombreServicio" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">
                <label for="descripcionServicioEdit">Description:</label>
                <input type="text" id="descripcionServicioEdit" name="descripcionServicio" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">
                <button type="submit">Save changes</button>
            </form>
            <div id="resultadoEdicion"></div>
        </div>
    </div>
    <script>
// Obtener los elementos necesarios
const modal = document.getElementById("modalEditarServicio");
const closeModalBtn = document.getElementById("closeModal");

// Función para cerrar el modal
closeModalBtn.onclick = function() {
    modal.style.display = "none";
}

// Cerrar el modal al hacer clic fuera de él
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</section>

<section id="gestion-pedidos">
    <h2>Order Management</h2>
    <p>Manage orders and update their status.</p>

    <form id="formPedido">
    <label for="cliente">Customer:</label>
    <select name="cliente" id="cliente" required>
        <?php
        include 'config.php'; // Asegúrate de incluir la conexión a la base de datos
        $sql = "SELECT codigoCliente, nombre FROM cliente";
        $result = $conn->query($sql);

        echo "<option value='' disabled selected>-Select client-</option>";
        
        // Mostrar clientes en el dropdown
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['codigoCliente']}'>{$row['nombre']}</option>";
            }
        } else {
            echo "<option value=''>No hay clientes disponibles</option>";
        }
        ?>
    </select><br><br>

    <label for="fechaPedido">Order Date:</label>
    <input type="date" name="fechaPedido" id="fechaPedido" required><br><br>

    <label for="fechaEjecucion">Execution Date:</label>
    <input type="date" name="fechaEjecucion" id="fechaEjecucion" required><br><br>

    <label for="equipo">Equipment:</label>
    <select name="equipo" id="equipo" required>
        <?php
        include 'config.php';
        $sql= "SELECT codigoEqp, nombre FROM equipo";
        $result = $conn->query($sql);

        echo "<option value='' disabled selected>-Select Equipment-</option>";

         // Mostrar clientes en el dropdown
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['codigoEqp']}'>{$row['codigoEqp']}</option>";
            }
        } else {
            echo "<option value=''>No hay equipos disponibles</option>";
        }
        ?>
    </select><br><br>

    <label for="servicio">Service:</label>
    <select name="servicio" id="servicio" required>
        <?php
        include 'config.php';
        $sql= "SELECT codigo, nombre FROM servicio";
        $result = $conn->query($sql);

        echo "<option value='' disabled selected>-Select Service-</option>";

         // Mostrar clientes en el dropdown
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                
                echo "<option value='{$row['codigo']}'>{$row['nombre']}</option>";
            }
        } else {
            echo "<option value=''>No hay servicios disponibles</option>";
        }
        ?>
    </select><br><br>

    <label for="estado">Status:</label>
    <select name="estado" id="estado" required>
        <option value="Select">-Select status-</option>
        <option value="Pending">Pending</option>
        <option value="In Process">In Process</option>
        <option value="Completed">Completed</option>
        <option value="Canceled">Canceled</option>
    </select><br><br>

    <input type="submit" value="Insert Order">
</form>

<!-- Este es el contenedor para mostrar el mensaje de éxito o error -->
<div id="mensaje" style="display:none;"></div>
<script>
// Enviar el formulario sin recargar la página

// Enviar el formulario sin recargar la página
document.getElementById('formPedido').addEventListener('submit', function(event) {
    event.preventDefault();  // Evita el envío tradicional del formulario

    let formData = new FormData(this);

    fetch('agregarPedido.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log(response);  // Verifica la respuesta completa en consola
        return response.json();  // Procesa la respuesta como JSON
    })
    .then(data => {
        console.log(data);  // Verifica los datos devueltos
        const mensaje = document.getElementById('mensaje');
        if (data.success) {
            mensaje.innerText = 'Order inserted correctly';
            mensaje.style.display = 'block';
            actualizarTabla(); // Actualiza la tabla sin recargar la página
        } else {
            mensaje.innerText = `Error: ${data.error}`;
            mensaje.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error de red:', error);
        document.getElementById('mensaje').innerText = 'There was a problem with the request';
        document.getElementById('mensaje').style.display = 'block';
    });
});

// Función para actualizar la tabla sin recargar la página
function actualizarTabla() {
    fetch('obtenerPedidos.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error in server response');
        }
        return response.json();
    })
    .then(data => {
        const tablaPedidos = document.getElementById('tablaPedidos').querySelector('tbody');
        
        // Limpiar el contenido actual de la tabla
        tablaPedidos.innerHTML = '';

        if (data.pedidos && data.pedidos.length > 0) {
            data.pedidos.forEach(pedido => {
                let row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>${pedido.CodigoPedido}</td>  <!-- Asegúrate de usar CodigoPedido -->
                    <td>${pedido.cliente}</td>
                    <td>${pedido.fechaPedido}</td>
                    <td>${pedido.estado}</td>
                    <td>
                        <button class='actualizarEstado' data-codigo='${pedido.CodigoPedido}'>Update status</button>
                        <button class='verDetalles' data-codigo='${pedido.CodigoPedido}'>View Details</button>
                    </td>
                `;
                tablaPedidos.appendChild(row);
            });

            // Asigna los eventos a los botones nuevamente
            agregarEventosBotones();
        } else {
            let row = document.createElement('tr');
            row.innerHTML = `<td colspan="5">No orders available.</td>`;
            tablaPedidos.appendChild(row);
        }
    })
    .catch(error => console.error('Error updating the table:', error));
}

// Función para agregar eventos a los botones de la tabla
function agregarEventosBotones() {
    document.querySelectorAll('.actualizarEstado').forEach(button => {
        button.addEventListener('click', function() {
            const codigo = this.getAttribute('data-codigo');
            actualizarEstado(codigo);
        });
    });

    document.querySelectorAll('.verDetalles').forEach(button => {
        button.addEventListener('click', function() {
            const codigo = this.getAttribute('data-codigo');
            verDetalles(codigo);
        });
    });
}

</script>



    <!-- Barra de búsqueda -->
     <div class="barra-busqueda">
        <input type="text" id="buscarPedido" placeholder="Search by code or customer..." onkeyup="filtrarPedidos()">
     </div>
    
    <!-- Tabla de pedidos -->
    <table id="tablaPedidos">
        <thead>
            <tr>
                <th>Code</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tablaPedidos">
            <!-- Aqui se cargaran los pedidos mediante PHP -->
            <?php
            include 'config.php';
            $sql = "SELECT * FROM pedido";
            $result = $conn->query($sql);


            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $codigo = isset($row['CodigoPedido']) ? $row['CodigoPedido'] : 'N/A';
                    $cliente = isset($row['cliente']) ? $row['cliente'] : 'N/A';
                    $fechaPedido = isset($row['fechaPedido']) ? $row['fechaPedido'] : 'N/A';
                    $estado = isset($row['estado']) ? $row['estado'] : 'N/A';
                    
                    echo "<tr data-codigo='{$codigo}' data-cliente='{$cliente}'>
                            <td>{$codigo}</td>
                            <td>{$cliente}</td>
                            <td>{$fechaPedido}</td>
                            <td>{$estado}</td>
                            <td>
                                <button class='actualizarEstado' data-codigo='{$codigo}'>Update status</button>
                                <button class='verDetalles' data-codigo='{$codigo}'>View details</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders to show.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
  
    <!-- Modal para actualizar estado del pedido -->
    <div class="modal" id="modalActualizarEstado">
        <div class="modal-content">
            <span class="close" id="closeModalEstado">&times;</span>
            <h3>Update Order Status</h3>
            <form id="formActualizarEstado" method="POST" action="actualizarEstadoPedido.php">
                <input type="hidden" id="codigoPedidoEstado" name="codigoPedido">
                <label for="estadoPedido">New Status:</label>
                <select id="estadoPedido" name="estadoPedido" required>
                    <option value="Pending">Pending</option>
                    <option value="Review">Review</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <button type="submit">Update</button>
            </form>
            <div id="resultadoActualizacion"></div>
        </div>
    </div>

    <script>
// Función para filtrar los pedidos por código o cliente
function filtrarPedidos() {
    var input = document.getElementById("buscarPedido");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("tablaPedidos");
    var tr = table.getElementsByTagName("tr");

    // Iterar por todas las filas de la tabla y ocultar las que no coincidan con la búsqueda
    for (var i = 0; i < tr.length; i++) {
        var tdCodigo = tr[i].getElementsByTagName("td")[0];
        var tdCliente = tr[i].getElementsByTagName("td")[1];
        if (tdCodigo || tdCliente) {
            var codigoValue = tdCodigo.textContent || tdCodigo.innerText;
            var clienteValue = tdCliente.textContent || tdCliente.innerText;
            if (codigoValue.toUpperCase().indexOf(filter) > -1 || clienteValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

</script>

        <!-- Modal para ver detalles del pedido -->
<div class="modal" id="modalVerDetalles">
    <div class="modal-content">
        <span class="close" id="closeModalDetalles">&times;</span>
        <h3>Order Details</h3>
        <div id="contenidoDetalles">
            <!-- Aquí se mostrarán los detalles del pedido -->
        </div>
    </div>
</div>
</section>



<!-- Sección de Control de Equipos -->
<section id="control-equipos">
    <h2>Equipment Control</h2>
    
    <form id="agregar-equipo-form" onsubmit="event.preventDefault(); agregarEquipo();">
        <label for="nombre">Equipment name:</label>
        <input type="text" id="nombre" name="nombre" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">

        <label for="marca">Brand:</label>
        <input type="text" id="marca" name="marca" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">

        <label for="categoria">Category:</label>
        <select id="categoria" name="categoria" required>
            <option value="">Select a category</option>
            <!-- Las opciones de categorías se llenarán aquí dinámicamente -->
        </select>

        <label for="descripcion">Description:</label>
        <input type="text" id="descripcion" name="descripcion" required pattern="[A-Za-zÁ-ÿ\s]+" title="Only letters and spaces are allowed.">

        <button type="submit" id="btn-agregar">Add Equipment</button>
    </form>

    <!-- Caja de Notificación de Éxito o Error -->
    <div id="mensaje-notificacion" class="mensaje-notificacion" style="display: none;"></div>

    <h3>Equipment List</h3>
    <div id="lista-equipos">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="equipos-list">
                <!-- Las filas de los equipos se agregarán dinámicamente aquí -->
            </tbody>
        </table>
    </div>

    <!-- Ventana Modal de Edición -->
    <div id="modal-edicion" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3>Edit Equipment</h3>
            <label for="edit-nombre">Name:</label>
            <input type="text" id="edit-nombre" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">

            <label for="edit-marca">Brand:</label>
            <input type="text" id="edit-marca" required pattern="[A-Za-zÀ-ÿ\s]+" title="Only letters and spaces allowed.">

            <label for="edit-categoria">Category:</label>
            <select id="edit-categoria" required>
                <option value="">Select Category</option>
                <!-- Las opciones de categorías se llenarán aquí dinámicamente -->
            </select>

            <label for="edit-descripcion">Description:</label>
            <input type="text" id="edit-descripcion" name="descripcion" required pattern="[A-Za-zÁ-ÿ\s]+" title="Only letters and spaces are allowed.">

            <input type="hidden" id="edit-codigo"> <!-- Campo oculto para el código del equipo -->
            <button id="guardarCambios" class="btn-guardar">Save Changes</button>
            <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cancel</button>
        </div>
    </div>

    <script>
        function agregarEquipo() {
            // Deshabilitar el botón de agregar equipo
            const btnAgregar = document.getElementById('btn-agregar');
            btnAgregar.disabled = true;

            // Obtener los valores del formulario
            const nombre = document.getElementById('nombre').value;
            const marca = document.getElementById('marca').value;
            const categoria = document.getElementById('categoria').value;
            const descripcion = document.getElementById('descripcion').value;

            // Crear el objeto FormData para enviar al servidor
            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('marca', marca);
            formData.append('categoria', categoria);
            formData.append('descripcion', descripcion);

            // Hacer la solicitud al servidor
            fetch('agregarEquipo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())  // Convertir la respuesta a formato JSON
            .then(data => {
                // Mostrar mensaje de éxito o error
                const mensajeNotificacion = document.getElementById('mensaje-notificacion');
                if (data.success) {
                    // Mostrar el mensaje de éxito
                    mensajeNotificacion.textContent = data.message;
                    mensajeNotificacion.style.backgroundColor = '#28a745';  // Verde (éxito)
                    mensajeNotificacion.style.display = 'block';  // Mostrar la caja
                } else {
                    // Mostrar el mensaje de error
                    mensajeNotificacion.textContent = data.message;
                    mensajeNotificacion.style.backgroundColor = '#dc3545';  // Rojo (error)
                    mensajeNotificacion.style.display = 'block';  // Mostrar la caja
                }

                // Solo agregar la nueva fila si no existe ya
                const equiposList = document.getElementById('equipos-list');
                const existingRows = document.querySelectorAll(`#equipos-list tr[data-codigo="${data.codigo}"]`);

                if (data.success && existingRows.length === 0) {
                    const row = document.createElement('tr');
                    row.dataset.codigo = data.codigo;  // Usar un data-codigo único para la fila
                    row.innerHTML = `
                        <td>${data.codigo}</td>
                        <td>${nombre}</td>
                        <td>${marca}</td>
                        <td>${data.categoriaNombre}</td>
                        <td>${descripcion}</td>
                        <td>
                            <button class="editar" onclick="editarEquipo('${data.codigo}')">Edit</button>
                            <button class="eliminar" onclick="eliminarEquipo('${data.codigo}')">Delete</button>
                        </td>
                    `;
                    equiposList.appendChild(row);  // Agregar la nueva fila a la tabla
                }

                // Rehabilitar el botón de agregar equipo
                btnAgregar.disabled = false;

                // Limpiar el formulario después de agregar
                document.getElementById('agregar-equipo-form').reset();
            })
            .catch(error => {
                console.error('Error when adding equipment:', error);
                const mensajeNotificacion = document.getElementById('mensaje-notificacion');
                mensajeNotificacion.textContent = 'Unexpected error. Please try again.';
                mensajeNotificacion.style.backgroundColor = '#dc3545';  // Rojo (error)
                mensajeNotificacion.style.display = 'block';  // Mostrar la caja

                // Rehabilitar el botón de agregar equipo
                btnAgregar.disabled = false;
            });
        }

        function eliminarEquipo(codigo) {
            // Deshabilitar los botones mientras se procesa la solicitud
            const btnEliminar = document.querySelector(`button[onclick="eliminarEquipo('${codigo}')"]`);
            btnEliminar.disabled = true;

            // Hacer la solicitud de eliminación al servidor
            fetch('eliminarEquipo.php', {
                method: 'POST',
                body: JSON.stringify({ codigo: codigo }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())  // Convertir la respuesta a formato JSON
            .then(data => {
                // Mostrar mensaje de éxito o error
                const mensajeNotificacion = document.getElementById('mensaje-notificacion');
                if (data.success) {
                    // Eliminar la fila del equipo de la tabla
                    const row = document.querySelector(`#equipos-list tr[data-codigo="${codigo}"]`);
                    row.remove();

                    mensajeNotificacion.textContent = data.message;
                    mensajeNotificacion.style.backgroundColor = '#28a745';  // Verde (éxito)
                    mensajeNotificacion.style.display = 'block';  // Mostrar la caja
                } else {
                    mensajeNotificacion.textContent = data.message;
                    mensajeNotificacion.style.backgroundColor = '#dc3545';  // Rojo (error)
                    mensajeNotificacion.style.display = 'block';  // Mostrar la caja
                }

                // Rehabilitar el botón de eliminar equipo
                btnEliminar.disabled = false;
            })
            .catch(error => {
                console.error('Error when deleting the equipment:', error);
                const mensajeNotificacion = document.getElementById('mensaje-notificacion');
                mensajeNotificacion.textContent = 'Unexpected error. Please try again.';
                mensajeNotificacion.style.backgroundColor = '#dc3545';  // Rojo (error)
                mensajeNotificacion.style.display = 'block';  // Mostrar la caja

                // Rehabilitar el botón de eliminar equipo
                btnEliminar.disabled = false;
            });
        }
    </script>
</section>






<section id="certificados">
    <h2>Certificates Query</h2>
    <p>The list of certificates uploaded by the technicians is shown below.</p>

    <!-- Barra de búsqueda -->
    <form method="GET" action="">
        <label for="busqueda">Certificate search:</label>
        <input type="text" id="busqueda" name="busqueda" placeholder="Enter code or equipment...">
    </form>

    <!-- Tabla de certificados -->
    <table>
        <thead>
            <tr>
                <th>Certificate code</th>
                <th>Equipment code</th>
                <th>Date of Issue</th>
                <th>Expiration Date</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody id="certificados-lista">
            <!-- Aquí se mostrarán los resultados de los certificados -->
        </tbody>
    </table>

    <script>
    // Función para cargar todos los certificados o filtrar por búsqueda
    function cargarCertificados(busqueda = '') {
        // Crear una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'buscar_certificados.php?busqueda=' + busqueda, true);

        // Cuando el servidor responda, actualizar la tabla
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('certificados-lista').innerHTML = xhr.responseText;
            }
        };

        // Enviar la solicitud
        xhr.send();
    }

    // Llamar a la función al cargar la página para mostrar todos los certificados por defecto
    window.onload = function() {
        cargarCertificados();
    };

    // Añadir el evento keyup al campo de búsqueda para filtrar en tiempo real
    document.getElementById('busqueda').addEventListener('keyup', function() {
        // Obtener el valor de búsqueda
        var busqueda = this.value;
        // Llamar a la función cargarCertificados con el término de búsqueda
        cargarCertificados(busqueda);
    });
</script>
</section>




<section id="alertas">
    <h2>Alerts and Notifications</h2>

    <!-- Contenedor de alertas -->
    <div id="contenedor-alertas">
        <?php include 'generarAlertas.php'; ?>
    </div>
</section>

<section id="request">
<h2>Request Query</h2>
<p>Query of Customer Requests.</p>

    <table>
        <thead>
            <tr>
                <th>Request</th>
                <th>Client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="Request-list">
        </tbody>
    </table>
    <script>
    // Función para cargar los datos
    async function cargarDatos() {
        try {
            const response = await fetch('MostrarSolicitud.php'); // Cambia a la ruta correcta si es necesario
            const datos = await response.json();

            // Verifica si hay algún error
            if (datos.error) {
                console.error("Error:", datos.error);
                return;
            }

            const tableBody = document.querySelector('#Request-list'); // Selecciona el tbody por su id
            tableBody.innerHTML = ''; // Limpia el contenido anterior

            datos.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.solicitud}</td>
                    <td>${row.cliente}</td>
                `;
                tableBody.appendChild(tr);
            });
        } catch (error) {
            console.error("Error al cargar los datos:", error);
        }
    }

    // Llamar a la función cuando se cargue la página
    document.addEventListener('DOMContentLoaded', cargarDatos);
</script>

<div class="modal" id="modal-see-details" style="display: none;">
    <div class="modal-content">
        <span class="close" id="close-modal-details">&times;</span>
        <h3>Order Details</h3>
        <p><strong>Request Number:</strong> <span id="detalleNumero"></span></p>
        <p><strong>Description:</strong> <span id="detalleDescripcion"></span></p>
    </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-see-details');
    const closeModal = document.getElementById('close-modal-details');

    // Función para cargar los detalles de la solicitud
    async function cargarDetalles(numeroSolicitud) {
    try {
        // Realiza la solicitud como antes, pero obtenemos el contenido como texto
        const response = await fetch(`ObtenerDetallesSolicitud.php?numero=${numeroSolicitud}`);
        const text = await response.text(); // Obtén la respuesta como texto

        // Intenta parsear la respuesta como JSON
        try {
            const datos = JSON.parse(text); // Intenta convertir el texto en JSON
            console.log('Detalles recibidos:', datos);

            if (datos.error) {
                console.error('Error:', datos.error);
                return;
            }

            // Mostrar los detalles en el modal
            document.getElementById('detalleNumero').textContent = datos.numero_solicitud;
            document.getElementById('detalleDescripcion').textContent = datos.descripcion;

            // Mostrar el modal
            modal.style.display = 'block';
        } catch (error) {
            console.error('Error al parsear el JSON:', error);
            console.error('Respuesta del servidor:', text); // Muestra la respuesta del servidor para depurar
        }
        
    } catch (error) {
        console.error('Error al cargar los detalles:', error);
    }
}

    // Función para cerrar el modal cuando se hace clic en la "X"
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Función para cargar los datos de la tabla
    async function cargarDatos() {
        try {
            const response = await fetch('MostrarSolicitud.php'); 
            const datos = await response.json();

            if (datos.error) {
                console.error('Error:', datos.error);
                return;
            }

            const tableBody = document.querySelector('#Request-list');
            tableBody.innerHTML = ''; // Limpia el contenido anterior

            datos.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.solicitud}</td>
                    <td>${row.cliente}</td>
                    <td>
                        <button class="ver-detalles-btn" data-id="${row.solicitud}">View Details</button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });

            // Agregar los eventos a los botones dinámicamente
            document.querySelectorAll('.ver-detalles-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const numeroSolicitud = this.getAttribute('data-id');
                    console.log('Botón presionado, número de solicitud:', numeroSolicitud);  
                    cargarDetalles(numeroSolicitud);
                });
            });
        } catch (error) {
            console.error('Error al cargar los datos:', error);
        }
    }

    // Llamar a la función cuando se cargue la página
    cargarDatos();
});


</script>


</section>

<section id="stadistics">
<h2>Stadistics of page</h2>
<p>Stadistics of the different data in the page.</p>

<script src="/Chart.min.js"></script>

<h2 style="text-align: center;">Number of orders per month</h2>

<canvas id="GraficaPedidos" width="800" height="200"></canvas>



<script>
// Función para obtener los datos de la consulta PHP
function obtenerDatos(mes) {
    return $.ajax({
        url: 'pedidosPorAño.php', // El archivo PHP que contiene la consulta
        method: 'GET',
        data: { mes: mes }, // Pasamos el mes como parámetro en la URL
        dataType: 'json',
    });
}

// Datos de ejemplo para los meses (puedes ajustar esto según lo que quieras mostrar)
var meses = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
var pedidos = [];

// Obtener los datos para cada mes y luego renderizar el gráfico
$(document).ready(function() {
    var promises = [];
    for (let i = 1; i <= 12; i++) {
        // Obtener los datos de cada mes (1 a 12)
        promises.push(
            obtenerDatos(i).then(function(data) {
                pedidos.push(data.pedido); // Almacenar el número de pedidos en el arreglo
            })
        );
    }

    // Esperamos que todas las solicitudes AJAX se completen
    Promise.all(promises).then(function() {
        // Crear la gráfica después de obtener todos los datos
        var ctx = document.getElementById('GraficaPedidos').getContext('2d');
        var miGrafica = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico: barra
            data: {
                labels: meses, // Etiquetas para cada mes
                datasets: [{
                    label: 'Number of Orders',
                    data: pedidos, // Datos de pedidos por mes
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Inicia el eje Y en 0
                    }
                }
            }
        });
    });
});
</script>

<h2 style="text-align: center;">Number of users</h2>

<canvas id="graficaUsuarios" width="800" height="200"></canvas>

<script>
$(document).ready(function() {
    // Hacer la solicitud AJAX al archivo PHP
    $.ajax({
        url: 'cantidadUsuarios.php', // Archivo PHP que devuelve los datos
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Crear la gráfica con los datos obtenidos
            var ctx = document.getElementById('graficaUsuarios').getContext('2d');
            var miGrafica = new Chart(ctx, {
                type: 'bar', // Tipo de gráfico: barra
                data: {
                    labels: ['Administrators', 'Customers', 'Technicians'], // Etiquetas del eje X
                    datasets: [{
                        label: 'Quantity',
                        data: [data.administradores, data.clientes, data.tecnicos], // Datos desde el PHP
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ], // Colores de fondo
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ], // Colores del borde
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true // Inicia el eje Y en 0
                        }
                    }
                }
            });
        },
        error: function() {
            alert('Error al obtener los datos');
        }
    });
});
</script>



<h2 style="text-align: center;">Number of Equipments by Category</h2>

<canvas id="graficaEquiposCategoria" width="800" height="200"></canvas>

<script>

$(document).ready(function() {
    // Hacer la solicitud AJAX para obtener los datos de equipos por categoría
    $.ajax({
        url: 'consulta_equipos_categoria.php', // Archivo PHP que devuelve los datos
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Crear la gráfica con los datos obtenidos
            var ctx = document.getElementById('graficaEquiposCategoria').getContext('2d');
            var graficaEquiposCategoria = new Chart(ctx, {
                type: 'bar', // Tipo de gráfico: barra
                data: {
                    labels: data.categories, // Categorías en el eje X
                    datasets: [{
                        label: 'Number of Equipments',
                        data: data.equipment_count, // Cantidad de equipos en cada categoría
                        backgroundColor: 'rgba(104, 206, 150, 1)', // Color de fondo
                        borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true // Inicia el eje Y en 0
                        }
                    }
                }
            });
        },
        error: function() {
            alert('Error al obtener los datos');
        }
    });
});
</script>

<h2 style="text-align: center;">Number of Technicians per Area of Specialty</h2>

<!-- Canvas for the chart -->
<canvas id="techniciansAreaChart" width="800" height="200"></canvas>

<script>
$(document).ready(function() {
    // Hacer la solicitud AJAX para obtener los datos de técnicos por área
    $.ajax({
        url: 'tecnicosArea.php', // Archivo PHP que devuelve los datos
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Crear la gráfica con los datos obtenidos
            var ctx = document.getElementById('techniciansAreaChart').getContext('2d');
            var techniciansAreaChart = new Chart(ctx, {
                type: 'bar', // Tipo de gráfico: barra
                data: {
                    labels: data.areas, // Áreas en el eje X
                    datasets: [{
                        label: 'Number of Technicians by Area',
                        data: data.technician_count, // Cantidad de técnicos en cada área
                        backgroundColor: 'rgba(226, 0, 255, 0.8)', // Color de fondo
                        borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true // Inicia el eje Y en 0
                        }
                    }
                }
            });
        },
        error: function() {
            alert('Error al obtener los datos');
        }
    });
});
</script>





</section>





    <footer>
        <p>&copy; 2024 CaliRisk. All rights reserved.</p>
    </footer>
    <script src="./adminDashboard.js"></script>


</body>
</html>
