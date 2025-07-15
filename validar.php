<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_de_datos = "materias";

$conn = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo " Conexión exitosa a la base de datos.";
}

$usu = $_POST["usuario"];
$contra = $_POST["password"];


$sql = "SELECT nombre, password FROM usuario";
$resultado = $conn->query($sql);

if ($resultado->num_rows >= 0) {
    while($fila = $resultado->fetch_assoc()) {
        $us = $fila["nombre"];
        $co = $fila["password"];
    }
} else {
    echo "No se encontraron resultados.";
}

if ($usu == $us && $contra == $co) {
    header("Location: bienvenido.php");
    exit(); // Siempre es buena práctica finalizar el script después de redireccionar
} else {
    echo "Usuario no válido";
}

$conn->close();
?> 
