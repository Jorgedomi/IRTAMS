<?php
include 'conexion.php';
session_start();

// Validación de entrada
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Verifica que los campos no estén vacíos
if (empty($usuario) || empty($password)) {
    $_SESSION['error'] = 'Debes completar todos los campos.';
    header('Location: index.php');
    exit();
}

// Consulta preparada
$sql = "SELECT * FROM usuario WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['usuario'] = $row['nombre']; // Puedes guardar también ID u otros datos
        header("Location: bienvenido.php");
        exit();
    } else {
        $_SESSION['error'] = 'Contraseña incorrecta.';
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Usuario no encontrado.';
    header('Location: index.php');
    exit();
}

$stmt->close();
$conn->close();
?>