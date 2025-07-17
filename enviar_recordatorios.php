<?php

require_once '../PHPMailer-6.10.0/src/Exception.php';
require_once '../PHPMailer-6.10.0/src/PHPMailer.php';
require_once '../PHPMailer-6.10.0/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluye conexión (ajusta ruta)
require_once 'conexion.php';



// resto del código...
$mail = new PHPMailer(true);

// Aquí colocas la consulta correcta según tu tabla usuario y campo correo
$sql = "SELECT t.*, u.correo FROM tareas t
        JOIN usuario u ON t.id_usuario = u.id_usuario
        WHERE fecha_recordatorio = CURDATE()";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conn->error);
}

if ($resultado->num_rows === 0) {
    echo "No hay recordatorios para hoy.";
} else {
    while ($fila = $resultado->fetch_assoc()) {
        // Aquí envías el correo usando PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dominguezperez81@gmail.com';         // tu correo Gmail
            $mail->Password   = 'noxyxhkayvltvnmh'; // tu contraseña o contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('dominguezperez81@gmail.com', 'Gestion Academica');
            $mail->addAddress($fila['correo']); // correo del usuario

            $mail->isHTML(true);
            $mail->Subject = 'Recordatorio de tarea: ' . $fila['nombre_tarea'];
            $mail->Body    = '<b>Hola,</b><br>Tienes un recordatorio para la tarea:<br><br>' .
                '<b>Nombre:</b> ' . htmlspecialchars($fila['nombre_tarea']) . '<br>' .
                '<b>Descripción:</b> ' . htmlspecialchars($fila['descripcion']) . '<br>' .
                '<b>Fecha entrega:</b> ' . $fila['fecha_entrega'] . ' a las ' . $fila['hora_entrega'] . '<br><br>' .
                '¡No lo olvides!';

            $mail->AltBody = "Hola,\n\nTienes un recordatorio para la tarea:\n" .
                "Nombre: " . $fila['nombre_tarea'] . "\n" .
                "Descripción: " . $fila['descripcion'] . "\n" .
                "Fecha entrega: " . $fila['fecha_entrega'] . " a las " . $fila['hora_entrega'] . "\n\n" .
                "¡No lo olvides!";

            $mail->send();
            echo "Correo enviado a: " . $fila['correo'] . "<br>";
        } catch (Exception $e) {
            echo "Error al enviar correo a " . $fila['correo'] . ": {$mail->ErrorInfo}<br>";
        }
    }
}
