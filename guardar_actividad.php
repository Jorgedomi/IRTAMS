<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["id_usuario"])) {
    // Si no hay sesión iniciada, redirigir o mostrar mensaje
    echo "No tienes permiso para realizar esta acción.";
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

// Recibir y sanitizar datos del formulario
$id_materia = isset($_POST['id_materia']) ? intval($_POST['id_materia']) : 0;
$nombre_tarea = isset($_POST['nombre_tarea']) ? trim($_POST['nombre_tarea']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$fecha_entrega = isset($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
$hora_entrega = isset($_POST['hora_entrega']) ? $_POST['hora_entrega'] : null;
$fecha_recordatorio = isset($_POST['fecha_recordatorio']) ? $_POST['fecha_recordatorio'] : null;
$hora_recordatorio = isset($_POST['hora_recordatorio']) ? $_POST['hora_recordatorio'] : null;

// Validaciones básicas
if ($id_materia <= 0 || empty($nombre_tarea) || empty($fecha_entrega)) {
    echo "Datos obligatorios incompletos.";
    exit;
}

// Preparar la consulta SQL con sentencias preparadas (para evitar inyección SQL)
$stmt = $conn->prepare("INSERT INTO tareas (id_usuario, id_materia, nombre_tarea, descripcion, fecha_entrega, hora_entrega, fecha_recordatorio, hora_recordatorio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo "Error en la preparación de la consulta: " . $conn->error;
    exit;
}

// Asignar valores a los parámetros. Para los valores que pueden ser NULL, ajustar.
$hora_entrega = ($hora_entrega === '') ? null : $hora_entrega;
$fecha_recordatorio = ($fecha_recordatorio === '') ? null : $fecha_recordatorio;
$hora_recordatorio = ($hora_recordatorio === '') ? null : $hora_recordatorio;

// Bind de parámetros: 'i' entero, 's' string
$stmt->bind_param(
    "iissssss",
    $id_usuario,
    $id_materia,
    $nombre_tarea,
    $descripcion,
    $fecha_entrega,
    $hora_entrega,
    $fecha_recordatorio,
    $hora_recordatorio
);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Redirigir a la página principal o mostrar mensaje de éxito
    header("Location: actividades.php?id_materia=" . urldecode($id_materia));  // Cambia index.php por la página que quieras
    exit;
} else {
    echo "Error al guardar la tarea: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
