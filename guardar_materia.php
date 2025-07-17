
<?php
session_start();
include 'conexion.php';

$id_usuario = intval($_SESSION["id_usuario"]);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);


    // Verificamos si la variable correcta está vacía
    if (!empty($nombre)) {
       $stmt = $conn->prepare("INSERT INTO materia (nombre_materia, id_usuario) VALUES (?, ?)");
        $stmt->bind_param("si", $nombre, $id_usuario);

        if ($stmt->execute()) {
            header("Location: bienvenido.php");
        } else {
            echo "<script>
                    alert('❌ Error al registrar la Materia: " . $stmt->error . "');
                    window.location.href = 'bienvenido.php';
                 </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('⚠️ El campo de nombre no puede estar vacío.');
                window.location.href = 'bienvenido.php';
             </script>";
    }
}

$conn->close();
?>