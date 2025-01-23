<?php
// Configuración de conexión a la base de datos
include 'config.php';
// Resultados iniciales
$resultados = [
    "administradores" => 0,
    "clientes" => 0,
    "tecnicos" => 0
];

// Consulta para contar administradores
$sqlAdministradores = "SELECT COUNT(a.codigo) as administradores FROM administrador a";
$result = $conn->query($sqlAdministradores);
if ($result && $row = $result->fetch_assoc()) {
    $resultados["administradores"] = $row["administradores"];
}

// Consulta para contar clientes
$sqlClientes = "SELECT COUNT(c.codigocliente) as clientes FROM cliente c";
$result = $conn->query($sqlClientes);
if ($result && $row = $result->fetch_assoc()) {
    $resultados["clientes"] = $row["clientes"];
}

// Consulta para contar técnicos
$sqlTecnicos = "SELECT COUNT(t.codigo) as tecnicos FROM tecnico t";
$result = $conn->query($sqlTecnicos);
if ($result && $row = $result->fetch_assoc()) {
    $resultados["tecnicos"] = $row["tecnicos"];
}

// Cerrar la conexión
$conn->close();

// Devolver los resultados en formato JSON
echo json_encode($resultados);
?>
