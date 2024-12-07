<?php
// registrar.php
// Formulario para registrar un nuevo curso

$page_title = "Registrar Curso";
require_once "../../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../../controllers/cursoController.php';
    $cursoController = new CursoController();

    // Validar campos del formulario
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (empty($nombre) || empty($descripcion)) {
        $error_message = "Todos los campos son requeridos.";
    } else {
        // Intentar registrar el curso
        try {
            if ($cursoController->registrar($_POST)) {
                $success_message = "Curso registrado exitosamente.";
            } else {
                $error_message = "Error al registrar el curso. Por favor, intenta de nuevo.";
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $error_message = "Hubo un error al intentar registrar el curso: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Registrar Nuevo Curso</h2>

    <!-- Mostrar mensajes de error si existen -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <!-- Mostrar mensaje de éxito si el curso fue registrado correctamente -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Curso</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Ej: Curso de PHP" value="<?= isset($nombre) ? htmlspecialchars($nombre) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required placeholder="Descripción del curso"><?= isset($descripcion) ? htmlspecialchars($descripcion) : '' ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>


