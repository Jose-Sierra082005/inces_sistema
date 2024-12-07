<?php
// aperturas.php
// Gestión de aperturas de cursos

$page_title = "Apertura de Cursos";
require_once "../../includes/header.php";

require_once '../../controllers/cursoController.php';
$cursoController = new CursoController();

// Verificar si se ha proporcionado un ID de curso
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID del curso no proporcionado o inválido.";
    require_once "../../includes/footer.php";
    exit();
}

$curso = $cursoController->obtener($_GET['id']);
if (!$curso) {
    echo "Curso no encontrado.";
    require_once "../../includes/footer.php";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar fechas
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';

    // Validar que las fechas no estén vacías y que la fecha de inicio no sea posterior a la de fin
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        $error_message = "Ambas fechas son requeridas.";
    } elseif ($fecha_inicio > $fecha_fin) {
        $error_message = "La fecha de inicio no puede ser posterior a la fecha de fin.";
    } else {
        try {
            // Aperturar el curso
            $cursoController->aperturarCurso($_GET['id'], $_POST);
            $success_message = "Curso aperturado exitosamente.";
        } catch (Exception $e) {
            $error_message = "Hubo un error al aperturar el curso: " . $e->getMessage();
        }
    }
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Apertura del Curso: <?= htmlspecialchars($curso['nombre_curso']) ?></h3>
                </div>
                <div class="card-body">
                    <!-- Mostrar mensajes de error o éxito -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" value="<?= isset($fecha_inicio) ? htmlspecialchars($fecha_inicio) : '' ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" value="<?= isset($fecha_fin) ? htmlspecialchars($fecha_fin) : '' ?>" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aperturar Curso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
