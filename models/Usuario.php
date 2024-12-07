<?php
// Usuario.php
// Clase para gestionar los usuarios

require_once 'Database.php';

class Usuario
{
    private $conn;
    private $table_name = "usuario";

    public $id_usuario;
    public $nombre_usuario;
    public $contrasena;
    public $tipo_usuario;
    public $correo_electronico;

    public function __construct($dbConnection = null)
    {
        // Si no se pasa una conexión, crear una nueva
        if ($dbConnection === null) {
            $database = new Database();
            $this->conn = $database->getConnection();
        } else {
            $this->conn = $dbConnection;
        }
    }

    // Validar nombre de usuario
    private function validarNombreUsuario($nombre_usuario)
    {
        if (preg_match('/[^a-zA-Z0-9_]/', $nombre_usuario)) {
            return false; // Nombre de usuario contiene caracteres inválidos
        }
        return true;
    }

    // Registrar un nuevo usuario
    public function registrar($nombre_usuario, $contrasena, $tipo_usuario)
    {
        // Validar entrada
        if (empty($nombre_usuario) || empty($contrasena) || empty($tipo_usuario)) {
            return ["success" => false, "mensaje" => "Todos los campos son obligatorios"];
        }

        if (!$this->validarNombreUsuario($nombre_usuario)) {
            return ["success" => false, "mensaje" => "El nombre de usuario solo puede contener letras, números y guiones bajos"];
        }

        try {
            // Comprobar si el nombre de usuario ya existe
            $query = "SELECT * FROM " . $this->table_name . " WHERE nombre_usuario = :nombre_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ["success" => false, "mensaje" => "El nombre de usuario ya está en uso"];
            }

            $query = "INSERT INTO " . $this->table_name . " (nombre_usuario, contrasena, tipo_usuario) 
                      VALUES (:nombre_usuario, :contrasena, :tipo_usuario)";
            $stmt = $this->conn->prepare($query);

            // Hash de la contraseña
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':contrasena', $contrasenaHash);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Usuario registrado exitosamente"];
            } else {
                return ["success" => false, "mensaje" => "Error al registrar el usuario"];
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el usuario: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error de servidor. Por favor, inténtelo más tarde."];
        }
    }

    // Obtener usuario por nombre de usuario
    public function obtenerPorNombreUsuario($nombre_usuario)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE nombre_usuario = :nombre_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por nombre de usuario: " . $e->getMessage());
            return false;
        }
    }

    // Obtener usuario por ID
    public function obtener($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return false;
        }
    }

    // Editar usuario
    public function editar($id, $nombre_usuario, $contrasena, $tipo_usuario)
    {
        // Validar entrada
        if (empty($nombre_usuario) || empty($contrasena) || empty($tipo_usuario)) {
            return ["success" => false, "mensaje" => "Todos los campos son obligatorios"];
        }

        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET nombre_usuario = :nombre_usuario, contrasena = :contrasena, tipo_usuario = :tipo_usuario 
                      WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt->bindParam(':id_usuario', $id);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':contrasena', $contrasenaHash);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Usuario actualizado exitosamente"];
            } else {
                return ["success" => false, "mensaje" => "Error al actualizar el usuario"];
            }
        } catch (PDOException $e) {
            error_log("Error al editar el usuario: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error de servidor. Por favor, inténtelo más tarde."];
        }
    }
}


