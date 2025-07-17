<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

if (!isset($_GET["id"])) {
    echo "ID de materia no proporcionado.";
    exit;
}

$id_materia = intval($_GET["id"]);

// Verificar que la materia pertenezca al usuario actual
$sql = "SELECT * FROM materia WHERE id_materia = $id_materia AND id_usuario = $id_usuario";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    // Eliminar tareas relacionadas
    $conn->query("DELETE FROM tareas WHERE id_materia = $id_materia AND id_usuario = $id_usuario");

    // Eliminar la materia
    $conn->query("DELETE FROM materia WHERE id_materia = $id_materia AND id_usuario = $id_usuario");

    header("Location: bienvenido.php");
    exit;
} else {
    echo "Materia no encontrada o no tienes permiso para eliminarla.";
}
?>
