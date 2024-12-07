<?php
// registrar.php
// Formulario para registrar un nuevo participante

$page_title = "Registrar Participante";
require_once "../../includes/header.php";

// Verificar si el archivo de controlador está siendo cargado correctamente
require_once '../../controllers/participanteController.php';
$participanteController = new ParticipanteController();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar campos del formulario
    if (empty($_POST['tipo_identificacion']) || empty($_POST['identificacion']) || empty($_POST['nombres']) || empty($_POST['apellidos'])) {
        $error_message = "Todos los campos son requeridos.";
    } elseif (!is_numeric($_POST['identificacion'])) {
        // Validar que el número de identificación sea numérico
        $error_message = "El número de identificación debe ser numérico.";
    } else {
        if ($participanteController->registrar($_POST)) {
            $success_message = "Participante registrado exitosamente.";
        } else {
            $error_message = "Error al registrar el participante.";
        }
    }
}

?>

<div class="container mt-5">
    <h2 class="mb-4">Registrar Participante</h2>

    <!-- Mostrar mensajes de éxito y error -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <!-- Formulario para registrar un participante -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
            <select name="tipo_identificacion" class="form-select" required>
                <option value="Cédula" <?= (isset($_POST['tipo_identificacion']) && $_POST['tipo_identificacion'] == 'Cédula') ? 'selected' : '' ?>>Cédula</option>
                <option value="Pasaporte" <?= (isset($_POST['tipo_identificacion']) && $_POST['tipo_identificacion'] == 'Pasaporte') ? 'selected' : '' ?>>Pasaporte</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="identificacion" class="form-label">Número de Identificación</label>
            <input type="text" name="identificacion" class="form-control" value="<?= isset($_POST['identificacion']) ? htmlspecialchars($_POST['identificacion']) : '' ?>" required placeholder="Ej: 12345678">
        </div>

        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" name="nombres" class="form-control" value="<?= isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : '' ?>" required placeholder="Juan Carlos">
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?= isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : '' ?>" required placeholder="Pérez Gómez">
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<?php require_once "../../includes/footer.php"; ?>


