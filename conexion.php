<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "materias"; // Tu base

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
