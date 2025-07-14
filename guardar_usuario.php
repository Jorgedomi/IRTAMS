<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_materia = trim($_POST['nombre_materia']);

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO materias (nombre_materia) VALUES (?)");
        $stmt->bind_param("s", $nombre_materia);

        if ($stmt->execute()) {
            echo "<script>alert('Materia registrada correctamente'); window.location.href='bienvenido.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "El nombre no puede estar vacío.";
    }
}

$conn->close();
?>

