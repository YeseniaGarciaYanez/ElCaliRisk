<?php
// config.php
$host = 'localhost';
$db = 'caliriskdb';
$user = 'root';
$password = '';


$conn = new mysqli($host, $user, $password, $db);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
