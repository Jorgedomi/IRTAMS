<?php
session_start();
?>

<?php if (isset($_SESSION["id_usuario"])): ?>
<?php
include 'conexion.php';

$id_usuario = $_SESSION["id_usuario"];

// 🔎 Consulta para obtener las materias del usuario activo
$sql = "SELECT nombre_materia FROM materia WHERE id_usuario = $id_usuario";
$resultado = $conn->query($sql);

// 📦 Almacenar resultados en un arreglo
$materias = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $materias[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Materias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-3">
    <h1 class="text-center bg-success text-white py-4 w-100 m-0">Registro de Actividades</h1>

    <!-- Formulario para registrar materia -->
    <div class="card mt-4">
        <div class="card-body">
            <form action="guardar_materia.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la materia:</label>
                    <input type="text" id="nombre" class="form-control" name="nombre" placeholder="Ejemplo: Base de Datos" required>
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
        </div>
    </div>

    <!-- Tabla de materias registradas -->
    <div class="mt-5">
        <h4 class="mb-3">Materias registradas </h4>
        <?php if (count($materias) > 0): ?>
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th colspan="3">Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $materia): ?>
                        <tr>
                            <td><?= $materia["nombre_materia"] ?></td>
                            <td><a href="">Ver</a></td>
                            <td><a href="">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No hay materias registradas para este usuario.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php else: ?>
    <p class="alert alert-warning text-center">Sesión no iniciada</p>
<?php endif; ?>
