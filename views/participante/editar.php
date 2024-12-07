<?php
// editar.php
// Editar información de un participante

$page_title = "Editar Participante";
require_once "../../includes/header.php";
require_once '../../controllers/participanteController.php';

$participanteController = new ParticipanteController();

// Verificar que el ID de participante esté presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de participante inválido.";
    require_once "../../includes/footer.php";
    exit();
}

// Obtener datos del participante
$participante = $participanteController->obtener($_GET['id']);
if (!$participante) {
    echo "Participante no encontrado.";
    require_once "../../includes/footer.php";
    exit();
}

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiar y validar los datos del formulario
    $tipo_identificacion = trim($_POST['tipo_identificacion']);
    $identificacion = trim($_POST['identificacion']);
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);

    // Validación de campos vacíos
    if (empty($tipo_identificacion) || empty($identificacion) || empty($nombres) || empty($apellidos)) {
        $error_message = "Todos los campos son requeridos.";
    } else {
        // Intentar editar el participante
        try {
            if ($participanteController->editar($_GET['id'], $_POST)) {
                $success_message = "Participante actualizado exitosamente.";
                // Obtener los datos actualizados del participante
                $participante = $participanteController->obtener($_GET['id']);
            } else {
                $error_message = "Error al actualizar el participante. Por favor, intenta de nuevo.";
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $error_message = "Hubo un error al intentar actualizar el participante: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar Participante</h2>

    <!-- Mostrar mensaje de éxito si el participante fue actualizado correctamente -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <!-- Mostrar mensaje de error si ocurre un problema -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <!-- Formulario para editar el participante -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
            <select name="tipo_identificacion" class="form-select" required>
                <option value="Cédula" <?= $participante['tipo_identificacion'] == 'Cédula' ? 'selected' : '' ?>>Cédula</option>
                <option value="Pasaporte" <?= $participante['tipo_identificacion'] == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="identificacion" class="form-label">Número de Identificación</label>
            <input type="text" name="identificacion" id="identificacion" class="form-control" value="<?= htmlspecialchars($participante['identificacion']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" name="nombres" id="nombres" class="form-control" value="<?= htmlspecialchars($participante['nombres']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" value="<?= htmlspecialchars($participante['apellidos']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>


