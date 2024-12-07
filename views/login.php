<?php
// login.php
// Página de inicio de sesión

$page_title = "Iniciar sesión - Sistema INCES";
require_once "../includes/header.php";  // Incluir encabezado común

session_start();

// Verificar si el usuario ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . 'views/dashboard.php');
    exit();
}

$error_message = '';

// Procesar el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password)) {
        $error_message = "Por favor, ingrese tanto el usuario como la contraseña.";
    } else {
        // Aquí agregaríamos la lógica para validar el usuario contra la base de datos
        // Usamos una consulta preparada para evitar inyecciones SQL
        
        require_once "../includes/db.php";  // Incluir conexión a la base de datos

        try {
            // Consultar la base de datos para obtener el usuario
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM usuarios WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Si el usuario y la contraseña son correctos, iniciar sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_role'] = $user['role'];
                header('Location: ' . BASE_URL . 'views/dashboard.php');
                exit();
            } else {
                $error_message = "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error_message = "Error al conectarse a la base de datos. Inténtalo de nuevo más tarde.";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Iniciar sesión</h3>
                </div>
                <div class="card-body">
                    <!-- Mostrar mensajes de error si los hay -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username">Usuario</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
