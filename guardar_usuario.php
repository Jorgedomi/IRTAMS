
<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = trim($_POST['nombre']);
    $password = trim($_POST['password']);
    $edad = trim($_POST['edad']);
    $fecha = trim($_POST['fecha']);

    // Verificamos si la variable correcta está vacía
    if (!empty($nombre_usuario)) {
       $stmt = $conn->prepare("INSERT INTO usuario (nombre, password, edad, fecha_nacimiento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nombre_usuario, $password, $edad, $fecha);

        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ usuario registrado correctamente.');
                    window.location.href = 'index.php';
                 </script>";
        } else {
            echo "<script>
                    alert('❌ Error al registrar el usuario: " . $stmt->error . "');
                    window.location.href = 'registrar.php';
                 </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('⚠️ El campo de nombre no puede estar vacío.');
                window.location.href = 'registrar.php';
             </script>";
    }
}

$conn->close();
?>