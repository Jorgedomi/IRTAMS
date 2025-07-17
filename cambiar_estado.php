<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["id_usuario"])) {
    echo 'Sesión no iniciada';
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

if (!isset($_GET['id']) || !isset($_GET['id_materia'])) {
    echo 'Parámetros faltantes.';
    exit;
}

$id_tarea = intval($_GET['id']);
$id_materia = intval($_GET['id_materia']);

// Verificar que la tarea pertenece al usuario
$sql = "SELECT * FROM tareas WHERE id_tarea = $id_tarea AND id_usuario = $id_usuario AND id_materia = $id_materia";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo 'Tarea no encontrada o sin permiso.';
    exit;
}

// Cambiar estado, si es "pendiente" pasar a "entregada", si ya está entregada, puede regresar a "pendiente"
$tarea = $result->fetch_assoc();
$nuevo_estado = ($tarea['estado'] === 'entregada') ? 'pendiente' : 'entregada';

$sql_update = "UPDATE tareas SET estado = '$nuevo_estado' WHERE id_tarea = $id_tarea";
if ($conn->query($sql_update)) {
    header("Location: tareas.php?id_materia=$id_materia");
    exit;
} else {
    echo 'Error al actualizar estado.';
}
?>
