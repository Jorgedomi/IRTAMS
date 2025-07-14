<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Materias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-3">
    <h1 class="text-center bg-success text-white py-4 w-100 m-0">Registro de Materias</h1>

    <div class="card mt-8">
        <div class="card-body">
            <form action="guardar_usuario.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la materia:</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Ejemplo: Base de Datos" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

