<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["id_usuario"])) {
    echo '<p class="alert alert-warning text-center">Sesión no iniciada</p>';
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

// ✅ Verifica que venga id_materia por GET
if (!isset($_GET['id_materia'])) {
    echo '<p class="alert alert-danger text-center">Materia no especificada.</p>';
    exit;
}

$id_materia = intval($_GET['id_materia']);

// ✅ Consulta para mostrar nombre de la materia
$sql_materia = "SELECT nombre_materia FROM materia WHERE id_materia = $id_materia AND id_usuario = $id_usuario";
$res_materia = $conn->query($sql_materia);
if (!$res_materia || $res_materia->num_rows == 0) {
    echo '<p class="alert alert-danger text-center">No tienes acceso a esta materia.</p>';
    exit;
}
$materia = $res_materia->fetch_assoc();

// ✅ Filtrar SOLO tareas de esa materia
$sql_tareas = "SELECT * FROM tareas WHERE id_usuario = $id_usuario AND id_materia = $id_materia ORDER BY fecha_entrega DESC";
$resultado_tareas = $conn->query($sql_tareas);

$tareas = [];
if ($resultado_tareas && $resultado_tareas->num_rows > 0) {
    while ($fila = $resultado_tareas->fetch_assoc()) {
        $tareas[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas de <?= htmlspecialchars($materia['nombre_materia']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center bg-success text-white py-4 w-100 m-0">
        Tareas de <?= htmlspecialchars($materia['nombre_materia']) ?>
    </h1>

    <div class="mt-4 mb-4 d-flex justify-content-start gap-3">
        <a href="bienvenido.php" class="btn btn-success">
            ← Volver a Materias
        </a>
        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarTarea">
            Agregar Tarea
        </button>
    </div>


    <!-- Modal para agregar tarea -->
    <div class="modal fade" id="modalAgregarTarea" tabindex="-1" aria-labelledby="modalAgregarTareaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="guardar_actividad.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalAgregarTareaLabel">Agregar Tarea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Materia (oculta) -->
                        <input type="hidden" name="id_materia" value="<?= $id_materia ?>">

                        <!-- Nombre de la tarea -->
                        <div class="mb-3">
                            <label for="nombre_tarea" class="form-label">Nombre de la tarea</label>
                            <input type="text" class="form-control" id="nombre_tarea" name="nombre_tarea" placeholder="Ej. Ensayo, Proyecto..." required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción detallada"></textarea>
                        </div>

                        <!-- Fecha de entrega -->
                        <div class="mb-3">
                            <label for="fecha_entrega" class="form-label">Fecha de entrega</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" required>
                        </div>

                        <!-- Hora de entrega -->
                        <div class="mb-3">
                            <label for="hora_entrega" class="form-label">Hora de entrega</label>
                            <input type="time" class="form-control" id="hora_entrega" name="hora_entrega">
                        </div>

                        <!-- Fecha de recordatorio -->
                        <div class="mb-3">
                            <label for="fecha_recordatorio" class="form-label">Fecha de recordatorio</label>
                            <input type="date" class="form-control" id="fecha_recordatorio" name="fecha_recordatorio">
                        </div>

                        <!-- Hora de recordatorio -->
                        <div class="mb-3">
                            <label for="hora_recordatorio" class="form-label">Hora de recordatorio</label>
                            <input type="time" class="form-control" id="hora_recordatorio" name="hora_recordatorio">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de tareas registradas -->
    <div class="mt-5">
        <h4 class="mb-3">Tareas registradas</h4>
        <?php if (count($tareas) > 0): ?>
            <table class="table table-bordered">
                <thead class="table-success">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha Entrega</th>
                    <th>Hora Entrega</th>
                    <th>Estado</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tareas as $tarea): ?>
                    <?php
                    $fecha_hoy = date('Y-m-d');
                    $estado = '';
                    $color = '';
                    if ($tarea["estado"] === "entregada") {
                        $estado = "Entregada";
                        $color = "success";
                    } elseif ($tarea["estado"] === "pendiente") {
                        $fecha_hoy = date('Y-m-d');
                        if ($fecha_hoy > $tarea["fecha_entrega"]) {
                            $estado = "No entregada";
                            $color = "danger";
                        } else {
                            $estado = "Pendiente";
                            $color = "secondary";
                        }
                    } else {
                        $estado = "Desconocido";
                        $color = "dark";
                    }

                    ?>
                    <tr>
                        <td><?= htmlspecialchars($tarea["nombre_tarea"]) ?></td>
                        <td><?= nl2br(htmlspecialchars($tarea["descripcion"])) ?></td>
                        <td><?= htmlspecialchars($tarea["fecha_entrega"]) ?></td>
                        <td><?= htmlspecialchars($tarea["hora_entrega"]) ?></td>
                        <!-- 🔗 Ahora clicable -->
                        <td>
                            <a href="cambiar_estado.php?id=<?= $tarea['id_tarea'] ?>&id_materia=<?= $id_materia ?>"
                               class="badge bg-<?= $color ?>"
                               onclick="return confirm('¿Marcar como entregada?');">
                                <?= $estado ?>
                            </a>
                        </td>
                        <td><a href="eliminar_tarea.php?id=<?= $tarea['id_tarea'] ?>" onclick="return confirm('¿Seguro que quieres eliminar esta tarea?');"  class="btn btn-outline-danger">Eliminar</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No hay tareas registradas para esta materia.</div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
