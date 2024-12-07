<?php
// listar.php
// Página para listar cursos

$page_title = "Listado de Cursos";
require_once "../../includes/header.php";

// Verificar si el archivo de controlador está siendo cargado correctamente
require_once '../../controllers/cursoController.php';

// Verificar si la clase existe antes de instanciarla
if (class_exists('CursoController')) {
    $cursoController = new CursoController();
    try {
        $cursos = $cursoController->listar(); // Intentamos obtener la lista de cursos
    } catch (Exception $e) {
        // Manejo de errores si la obtención de cursos falla
        $error_message = "Hubo un error al obtener los cursos: " . $e->getMessage();
    }
} else {
    $error_message = "Error: La clase CursoController no fue encontrada.";
}

?>

<div class="container mt-5">
    <h2 class="mb-4">Cursos</h2>
    <a href="registrar.php" class="btn btn-success mb-3">Registrar nuevo curso</a>

    <!-- Mostrar mensajes de error si existen -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <!-- Tabla de Cursos -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre del Curso</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                        <td><?= htmlspecialchars($curso['descripcion']) ?></td>
                        <td>
                            <a href="registrar_apertura.php?id=<?= urlencode($curso['id_curso']) ?>" class="btn btn-primary">Gestionar Apertura</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No hay cursos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once "../../includes/footer.php"; ?>

