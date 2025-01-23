//JS para Gestion de Users

// adminDashboard.js
document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll("section");
    const menuItems = document.querySelectorAll(".sidebar ul li a");
    // Función para mostrar la sección activa y ocultar las demás 
 function mostrarSeccion(id) {
    const section = document.getElementById(id);
    if (section) {
        sections.forEach(section => section.style.display = "none");
        section.style.display = "block";
    } else {
        console.warn(`Sección con ID '${id}' no encontrada.`);
    }
}
    // Resaltar el elemento del menú activo
    function resaltarMenuActivo(anchor) {
        menuItems.forEach(item => item.classList.remove("active"));
        anchor.classList.add("active");
    }

    // Configuración inicial para mostrar solo la primera sección
    mostrarSeccion("gestion-usuarios");
    resaltarMenuActivo(menuItems[0]);

    // Event listener para el menú
    menuItems.forEach(anchor => {
        anchor.addEventListener("click", function(event) {
            event.preventDefault(); // Prevenir el comportamiento predeterminado
            const sectionId = this.getAttribute("href").substring(1);
            mostrarSeccion(sectionId);
            resaltarMenuActivo(this);
        });
    });
});


// JS para Administracion de servios

// Manejar el clic en los botones de editar
document.querySelectorAll('.editar').forEach(button => {
    button.addEventListener('click', function() {
        const codigo = this.getAttribute('data-codigo');
        const nombre = this.getAttribute('data-nombre');
        const descripcion = this.getAttribute('data-descripcion');
        cargarDatosServicio(codigo, nombre, descripcion);
    });
});

// Función para cargar los datos del servicio en el formulario de edición
function cargarDatosServicio(codigo, nombre, descripcion) {
    document.getElementById("codigoServicioEdit").value = codigo;
    document.getElementById("nombreServicioEdit").value = nombre;
    document.getElementById("descripcionServicioEdit").value = descripcion;
    document.getElementById("modalEditarServicio").style.display = "block"; // Mostrar el modal
}

// Manejar el envío del formulario de edición
document.addEventListener("DOMContentLoaded", function() {
    // Obtener el modal (asegurarse de que esta variable esté definida)
    const modal = document.getElementById("modalEditarServicio");

    // Asignar evento a cada botón de "Editar"
    const editarButtons = document.querySelectorAll(".editar");
    editarButtons.forEach(button => {
        button.addEventListener("click", function() {
            const codigo = this.getAttribute("data-codigo");
            const nombre = this.getAttribute("data-nombre");
            const descripcion = this.getAttribute("data-descripcion");

            // Llenar el formulario con los datos del servicio
            document.getElementById("codigoServicioEdit").value = codigo;
            document.getElementById("nombreServicioEdit").value = nombre;
            document.getElementById("descripcionServicioEdit").value = descripcion;

            // Mostrar el modal de edición
            modal.style.display = "block";
        });
    });

    // Cerrar el modal cuando el usuario hace clic en la "X"
    document.getElementById("closeModal").addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Cuando se envía el formulario de edición
    document.getElementById("formEditarServicio").addEventListener("submit", function(event) {
        event.preventDefault();  // Evitar que el formulario se envíe de manera predeterminada

        const formData = new FormData(this);

        // Enviar los datos al servidor usando fetch
        fetch("editarServicio.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())  // Aseguramos que recibimos la respuesta en formato JSON
        .then(data => {
            // Mostrar el resultado de la edición
            document.getElementById("resultadoEdicion").textContent = data.message;

            if (data.success) {
                // Actualizar la fila correspondiente en la tabla sin recargarla
                actualizarFilaTabla(data.codigo, data.nombre, data.descripcion);

                // Cerrar el modal y limpiar el formulario
                modal.style.display = "none";
                document.getElementById("formEditarServicio").reset();
            }
        })
        .catch(error => {
            document.getElementById("resultadoEdicion").textContent = "Ocurrió un error: " + error;
        });
    });

    // Función para actualizar la fila en la tabla
    function actualizarFilaTabla(codigo, nombre, descripcion) {
        // Encontrar la fila correspondiente usando el código
        const fila = document.querySelector(`tr[data-codigo='${codigo}']`);

        if (fila) {
            // Actualizar los valores en la fila
            fila.querySelector("td:nth-child(2)").textContent = nombre;
            fila.querySelector("td:nth-child(3)").textContent = descripcion;
        }
    }
});


// Función para eliminar un servicio
function eliminarServicio(codigo) {
    fetch("eliminarServicio.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ codigoServicio: codigo })
    })
    .then(response => {
        if (response.status === 204) {
            const eventoEliminacion = new CustomEvent('servicioEliminado', { detail: { codigo } });
            document.dispatchEvent(eventoEliminacion);
        } else {
            mostrarMensaje("Hubo un problema al eliminar el servicio");
        }
    })
    .catch(error => {
        mostrarMensaje("Ocurrió un error al eliminar el servicio");
    });
}

// Event listener para el botón de eliminar
document.querySelectorAll('.eliminar').forEach(button => {
    button.addEventListener('click', function() {
        const codigo = this.getAttribute('data-codigo');
        eliminarServicio(codigo);
    });
});

// Escuchar el evento personalizado 'servicioEliminado'
document.addEventListener('servicioEliminado', function(event) {
    const codigo = event.detail.codigo;
    const fila = document.querySelector(`tr[data-codigo="${codigo}"]`);
    if (fila) {
        fila.remove(); // Eliminar la fila de la tabla
        mostrarMensaje("Servicio eliminado exitosamente");
    }
});

// Función para mostrar mensajes
function mostrarMensaje(mensaje) {
    const contenedorMensajes = document.getElementById('contenedorMensajes');
    contenedorMensajes.innerText = mensaje;
    contenedorMensajes.style.display = 'block'; // Mostrar el mensaje
    setTimeout(() => {
        contenedorMensajes.style.display = 'none'; // Ocultar después de 3 segundos
    }, 3000);
}

// Función para filtrar servicios en la tabla
document.getElementById('buscarServicio').addEventListener('input', function() {
    var filtro = this.value.toLowerCase(); // Convertir a minúsculas para búsqueda insensible a mayúsculas
    var filas = document.querySelectorAll('table tbody tr');

    filas.forEach(function(fila) {
        var codigo = fila.querySelector('td:nth-child(1)').textContent.toLowerCase();
        var nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();

        // Mostrar la fila si coincide con el filtro en el código o el nombre
        if (codigo.includes(filtro) || nombre.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});


/* Gestion de pedidos*/
// Función para manejar el envío del formulario con AJAX



// Buscar pedidos por código o cliente
document.getElementById("buscarPedido").addEventListener("input", function() {
    const valorBusqueda = this.value.toLowerCase();
    const filas = document.querySelectorAll("#tablaPedidos tr");

    filas.forEach(fila => {
        const codigo = fila.querySelector("td:nth-child(1)").textContent.toLowerCase();
        const cliente = fila.querySelector("td:nth-child(2)").textContent.toLowerCase();
        
        if (codigo.includes(valorBusqueda) || cliente.includes(valorBusqueda)) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
});

// Mostrar modal para actualizar estado
document.querySelectorAll('.actualizarEstado').forEach(boton => {
    boton.addEventListener('click', () => {
        const codigo = boton.getAttribute('data-codigo');  // Obtiene el código del pedido
        document.getElementById('codigoPedidoEstado').value = codigo;  // Coloca el código en el campo oculto
        document.getElementById('modalActualizarEstado').style.display = 'block';  // Muestra el modal
    });
});

document.getElementById('formActualizarEstado').addEventListener('submit', function (e) {
    e.preventDefault();

    const codigoPedido = document.getElementById('codigoPedidoEstado').value;
    const estadoPedido = document.getElementById('estadoPedido').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'actualizarEstadoPedido.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);  // Verifica qué es lo que realmente está devolviendo el servidor
            try {
                const respuesta = JSON.parse(xhr.responseText);
                if (respuesta.success) {
                    // Actualizar la fila en la tabla
                    document.querySelector(`tr[data-codigo="${codigoPedido}"] td:nth-child(4)`).textContent = estadoPedido;
                    document.getElementById('resultadoActualizacion').textContent = respuesta.mensaje;
                } else {
                    document.getElementById('resultadoActualizacion').textContent = respuesta.mensaje;
                }
                // Cerrar el modal después de un breve retraso
                setTimeout(() => {
                    document.getElementById('modalActualizarEstado').style.display = 'none';
                    document.getElementById('resultadoActualizacion').textContent = '';
                }, 2000);
            } catch (e) {
                console.error("Error al parsear la respuesta JSON:", e);
            }
        }
    };
    
    xhr.send(`codigoPedido=${codigoPedido}&estadoPedido=${estadoPedido}`);
});
// Cerrar el modal al hacer clic en la "X"
document.getElementById('closeModalEstado').addEventListener('click', () => {
    document.getElementById('modalActualizarEstado').style.display = 'none';
});


// Detalles del pedido
document.querySelectorAll('.verDetalles').forEach(boton => {
    boton.addEventListener('click', () => {
        const codigo = boton.getAttribute('data-codigo');
        
        // Hacer una solicitud AJAX para obtener los detalles del pedido
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'obtenerDetallesPedido.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Mostrar los detalles en el modal
                document.getElementById('contenidoDetalles').innerHTML = xhr.responseText;
                document.getElementById('modalVerDetalles').style.display = 'block';
            }
        };
        xhr.send(`codigoPedido=${codigo}`);
    });
});

// Cerrar el modal al hacer clic en la "X"
document.getElementById('closeModalDetalles').addEventListener('click', () => {
    document.getElementById('modalVerDetalles').style.display = 'none';
});


// SECCION DE EQUIPOS Y HERRAMIENTAS


document.addEventListener("DOMContentLoaded", function() {
    cargarEquipos();
    cargarCategorias();

    const form = document.getElementById('agregar-equipo-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        agregarEquipo();
    });
});

function cargarEquipos() {
    fetch('listarEquipos.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const equiposList = document.getElementById('equipos-list');
            equiposList.innerHTML = '';
            data.data.forEach(equipo => {
                const row = `<tr>
                    <td>${equipo.codigoEqp}</td>
                    <td>${equipo.nombre}</td>
                    <td>${equipo.marca }</td>
                    <td>${equipo.categoria || 'No disponible'}</td>
                    <td>${equipo.descripcion}</td>
                    <td>
                        <button class="editar" onclick="editarEquipo('${equipo.codigoEqp}')">Edit</button>
                        <button class="eliminar" onclick="eliminarEquipo('${equipo.codigoEqp}')">Delete</button>
                    </td>
                </tr>`;
                equiposList.innerHTML += row;
            });
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));

}

function cargarCategorias() {
    fetch('obtenerCategorias.php')
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta de obtenerCategorias.php:', data);
            if (data.success) {
                const categoriaSelect = document.getElementById('categoria');
                const editCategoriaSelect = document.getElementById('edit-categoria');
                
                // Limpiar opciones antes de llenarlas
                categoriaSelect.innerHTML = '<option value="">Select a category</option>';
                editCategoriaSelect.innerHTML = '<option value="">Select a category</option>';

                data.data.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.codigo; // Asegúrate de que este campo corresponda a tu base de datos
                    option.textContent = categoria.nombre;

                    // Añadir opción al select de agregar equipo
                    categoriaSelect.appendChild(option.cloneNode(true)); // Usa cloneNode para duplicar el option

                    // Añadir opción al select de edición
                    editCategoriaSelect.appendChild(option);
                });
            } else {
                console.error('Error al cargar las categorías:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}




// Función para editar el equipo
function editarEquipo(codigo) {
    console.log("Editar equipo:", codigo);
    
    // Simulación de obtener los datos del equipo
    const equipo = {
        codigo: codigo,
        nombre: "Measuring Equipment",
        marca: "Brand A",
        categoria: "Category 1",
        descripcion: "Description of the measuring equipment."
    };
    
    // Llenar el formulario de edición con los datos
    document.getElementById('edit-nombre').value = equipo.nombre;
    document.getElementById('edit-marca').value = equipo.marca;
    document.getElementById('edit-categoria').value = equipo.categoria;
    document.getElementById('edit-descripcion').value = equipo.descripcion;
    document.getElementById('edit-codigo').value = equipo.codigo;
    
    // Mostrar la ventana modal (si la tienes)
    document.getElementById('modal-edicion').style.display = 'block';
    
    // Guardar los cambios al hacer clic en el botón "Guardar Cambios"
    document.getElementById('guardarCambios').onclick = function() {
        const nombre = document.getElementById('edit-nombre').value;
        const marca = document.getElementById('edit-marca').value;
        const categoria = document.getElementById('edit-categoria').value;
        const descripcion = document.getElementById('edit-descripcion').value;

        // Actualizar los datos sin refrescar la página
        actualizarFilaEquipo(codigo, nombre, marca, categoria, descripcion);
        cerrarModal();  // Cierra el modal después de guardar
    };
}

// Función para actualizar la fila en el DOM
function actualizarFilaEquipo(codigo, nombre, marca, categoria, descripcion) {
    // Buscar la fila correspondiente en el DOM (buscamos por el código del equipo)
    const filas = document.querySelectorAll('#equipos-list tr');
    
    // Iterar a través de las filas
    filas.forEach(fila => {
        const celdas = fila.getElementsByTagName('td');
        
        // Comparar el código del equipo con el que está en la primera celda
        if (celdas[0].textContent.trim() === codigo) {
            // Si encontramos la fila, actualizar las celdas correspondientes
            celdas[1].textContent = nombre;        // Actualiza nombre
            celdas[2].textContent = marca;         // Actualiza marca
            celdas[3].textContent = categoria;     // Actualiza categoría
            celdas[4].textContent = descripcion;   // Actualiza descripción
        }
    });
}

// Función para cerrar el modal (si la tienes)
function cerrarModal() {
    document.getElementById('modal-edicion').style.display = 'none';
}



// Función para cargar reportes
function cargarReportes() {
    fetch('obtenerReportes.php')
        .then(response => response.json())
        .then(data => {
            const listaReportes = document.getElementById('listaReportes');
            listaReportes.innerHTML = ''; 

            if (data.length > 0) {
                data.forEach(reporte => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <strong>${reporte.tipo}</strong> - Generado el: ${new Date(reporte.fechaGeneracion).toLocaleString()}
                        <a href="${reporte.archivo}" target="_blank" class="btn-download">Descargar</a>
                    `;
                    listaReportes.appendChild(listItem);
                });
            } else {
                listaReportes.innerHTML = '<li>No se han generado reportes aún.</li>';
            }
        })
        .catch(error => console.error('Error al cargar reportes:', error));
}


//ALERTAS

function openTab(evt, tabName) {
    let i, tabcontent, tablinks;
    
    // Oculta todos los contenidos de las pestañas
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Desactiva todas las pestañas
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Muestra la pestaña seleccionada y actívala
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

//REQUEST
