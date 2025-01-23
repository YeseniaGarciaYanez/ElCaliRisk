<?php
// Conexión a la base de datos
include 'config.php';

echo "<div class='alertas-container'>";

// Encabezado de pestañas
echo "
<div class='tabs'>
    <button class='tab-link active' onclick='openTab(event, \"Vencidos\")'>Expired Certificates and Equipment</button>
    <button class='tab-link' onclick='openTab(event, \"PorVencer\")'>Certificates and equipment to be expired</button>
</div>";

// Contenedor de contenido de pestañas
echo "<div id='Vencidos' class='tab-content' style='display: block;'>";

// Consulta para certificados vencidos
$consultaCertificadosVencidos = "SELECT codigo, fecha, DATE_ADD(fecha, INTERVAL intervalo MONTH) AS vence
                                 FROM certificado 
                                 WHERE DATE_ADD(fecha, INTERVAL intervalo MONTH) < CURDATE()";
$resultadoCertificadosVencidos = mysqli_query($conn, $consultaCertificadosVencidos);

if (mysqli_num_rows($resultadoCertificadosVencidos) > 0) {
    echo "<h3 class='alert-title'>Expired certificates:</h3>";
    while ($fila = mysqli_fetch_assoc($resultadoCertificadosVencidos)) {
        echo "<div class='alerta alerta-certificado'>
                <div class='alert-header'>
                    <span><strong>Certificate:</strong> " . $fila['codigo'] . "</span>
                </div>
                <div class='alert-body'>
                    <p><strong>Date of Issue:</strong> " . $fila['fecha'] . "</p>
                    <p><strong>Expired on:</strong> " . $fila['vence'] . "</p>
                </div>
              </div>";
    }
}

// Consulta para equipos vencidos
$consultaEquiposVencidos = "SELECT codigoEqp, nombre, proximaRecalibracion
                            FROM equipo
                            WHERE estado = 'Activo' AND proximaRecalibracion < CURDATE()";
$resultadoEquiposVencidos = mysqli_query($conn, $consultaEquiposVencidos);

if (mysqli_num_rows($resultadoEquiposVencidos) > 0) {
    echo "<h3 class='alert-title'>Expired equipment:</h3>";
    while ($fila = mysqli_fetch_assoc($resultadoEquiposVencidos)) {
        echo "<div class='alerta alerta-equipo'>
                <div class='alert-header'>
                    <span><strong>Equipment:</strong> " . $fila['nombre'] . " (" . $fila['codigoEqp'] . ")</span>
                </div>
                <div class='alert-body'>
                    <p><strong>Requires recalibration prior to:</strong> " . $fila['proximaRecalibracion'] . "</p>
                </div>
              </div>";
    }
}
echo "</div>"; // Cierra el contenido de la pestaña "Vencidos"

// Contenido de la pestaña "Por Vencer"
echo "<div id='PorVencer' class='tab-content'>";

// Consulta para certificados por vencer
$consultaCertificadosPorVencer = "SELECT codigo, fecha, DATE_ADD(fecha, INTERVAL intervalo MONTH) AS vence
                                  FROM certificado 
                                  WHERE DATE_ADD(fecha, INTERVAL intervalo MONTH) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
$resultadoCertificadosPorVencer = mysqli_query($conn, $consultaCertificadosPorVencer);

if (mysqli_num_rows($resultadoCertificadosPorVencer) > 0) {
    echo "<h3 class='alert-title'>Certificates close to expiration:</h3>";
    while ($fila = mysqli_fetch_assoc($resultadoCertificadosPorVencer)) {
        echo "<div class='alerta alerta-certificado'>
                <div class='alert-header'>
                    <span><strong>Certificate:</strong> " . $fila['codigo'] . "</span>
                </div>
                <div class='alert-body'>
                    <p><strong>Date of Issue:</strong> " . $fila['fecha'] . "</p>
                    <p><strong>Expiration date</strong> " . $fila['vence'] . "</p>
                </div>
              </div>";
    }
}

// Consulta para equipos próximos a recalibración
$consultaEquiposPorVencer = "SELECT codigoEqp, nombre, proximaRecalibracion
                             FROM equipo
                             WHERE estado = 'Activo' AND proximaRecalibracion BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
$resultadoEquiposPorVencer = mysqli_query($conn, $consultaEquiposPorVencer);

if (mysqli_num_rows($resultadoEquiposPorVencer) > 0) {
    echo "<h3 class='alert-title'>Equipment under recalibration:</h3>";
    while ($fila = mysqli_fetch_assoc($resultadoEquiposPorVencer)) {
        echo "<div class='alerta alerta-equipo'>
                <div class='alert-header'>
                    <span><strong>Equipment:</strong> " . $fila['nombre'] . " (" . $fila['codigoEqp'] . ")</span>
                </div>
                <div class='alert-body'>
                    <p><strong>Requires recalibration prior to:</strong> " . $fila['proximaRecalibracion'] . "</p>
                </div>
              </div>";
    }
}

echo "</div>"; // Cierra el contenido de la pestaña "Por Vencer"
echo "</div>"; // Cierra el contenedor general

mysqli_close($conn);
?>
