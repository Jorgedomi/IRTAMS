
<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $s = 3;

    // Verificamos si la variable correcta está vacía
    if (!empty($nombre)) {
       $stmt = $conn->prepare("INSERT INTO materia (nombre_materia, id_usuario) VALUES (?, ?)");
        $stmt->bind_param("si", $nombre, $s);

        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ Materia registrada correctamente.');
                    window.location.href = 'bienvenido.php';
                 </script>";
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