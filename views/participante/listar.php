<?php
// listar.php
// Listar participantes

$page_title = "Listado de Participantes";
require_once "../../includes/header.php";

require_once '../../controllers/participanteController.php';

// Verificar si la clase existe antes de instanciarla
if (class_exists('ParticipanteController')) {
    $participanteController = new ParticipanteController();
    // Obtener los participantes de la base de datos
    $participantes = $participanteController->listar();
} else {
    echo "Error: La clase ParticipanteController no fue encontrada.";
    require_once "../../includes/footer.php";
    exit();
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Listado de Participantes</h2>
    
    <a href="registrar.php" class="btn btn-primary mb-3">Registrar nuevo participante</a>

    <!-- Tabla de participantes -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Identificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($participantes)): ?>
                <tr><td colspan="3" class="text-center">No hay participantes registrados.</td></tr>
            <?php else: ?>
                <?php foreach ($participantes as $participante): ?>
                    <tr>
                        <td><?= htmlspecialchars($participante['nombres']) . " " . htmlspecialchars($participante['apellidos']) ?></td>
                        <td><?= htmlspecialchars($participante['identificacion']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= urlencode($participante['id_participante']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar.php?id=<?= urlencode($participante['id_participante']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este participante?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once "../../includes/footer.php"; ?>




