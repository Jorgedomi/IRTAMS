<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

if (!isset($_GET["id"])) {
    echo "ID de tarea no proporcionado.";
    exit;
}

$id_tarea = intval($_GET["id"]);

// Verificar que la tarea pertenezca al usuario actual
$sql = "SELECT * FROM tareas WHERE id_tarea = $id_tarea AND id_usuario = $id_usuario";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    // Eliminar la tarea
    $conn->query("DELETE FROM tareas WHERE id_tarea = $id_tarea AND id_usuario = $id_usuario");
    header("Location: {$_SERVER['HTTP_REFERER']}"); // Redirige a la página anterior
    exit;
} else {
    echo "Tarea no encontrada o no tienes permiso para eliminarla.";
}
?>
