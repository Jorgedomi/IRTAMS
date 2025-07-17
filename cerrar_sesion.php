<?php
session_start();
session_destroy();
header("Location: index.php"); // Cambia esto si tu archivo de login tiene otro nombre
exit;
?>

