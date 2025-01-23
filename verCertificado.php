<?php
// Incluir archivo de configuración
include 'config.php';

if (isset($_GET['codigo'])) {
    $codigoCertificado = $_GET['codigo'];

    $query = "
        SELECT 
            c.codigo AS codigoCertificado, 
            tc.equipo AS codigoEquipo, 
            c.fecha AS fechaExpedicion, 
            c.intervalo AS fechaVencimiento
        FROM certificado c
        JOIN tipo_certificado tc ON c.codigo = tc.certificado
        WHERE c.codigo = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $codigoCertificado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Detalles del Certificado</h2>";
            echo "<p><strong>Código del Certificado:</strong> " . $row['codigoCertificado'] . "</p>";
            echo "<p><strong>Código del Equipo:</strong> " . $row['codigoEquipo'] . "</p>";
            echo "<p><strong>Fecha de Expedición:</strong> " . $row['fechaExpedicion'] . "</p>";
            echo "<p><strong>Fecha de Vencimiento:</strong> " . $row['fechaVencimiento'] . "</p>";
        } else {
            echo "<p>No se encontró el certificado solicitado.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error en la consulta.</p>";
    }

    $conn->close();
} else {
    echo "<p>No se proporcionó un código de certificado válido.</p>";
}
?>
