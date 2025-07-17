<?php
session_start(); //Iniciar sesión

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_de_datos = "materias";


$conn = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 📥 Obtener datos del formulario
$usu = $_POST["usuario"];
$contra = $_POST["password"];

// 🔎 Consulta para validar usuario y contraseña
$sql = "SELECT id_usuario, nombre, password FROM usuario WHERE nombre = '$usu' AND password = '$contra'";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    //Guardar id_usuario en la sesión
    $_SESSION["id_usuario"] = $fila["id_usuario"];

    echo "<script>
                    alert('✅ Inicio de sesion correcta.');
                    window.location.href = 'bienvenido.php';
                 </script>";
    exit();
} else {
    echo "<script>
                    alert('Inicio de sesion incorrecta.');
                    window.location.href = 'index.php';
                 </script>";
}

$conn->close();
?>
