<?php

require_once 'Database.php';

class Participante
{
    private $conn;
    private $table_name = "participante";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Listar participantes con paginación
    public function listar($offset = 0, $limit = 10)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " LIMIT :offset, :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return ["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            error_log("Error al listar los participantes: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al listar los participantes."];
        }
    }

    // Registrar participante
    public function registrar($data)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (tipo_identificacion, identificacion, nombres, apellidos, telefono, sexo, fecha_nacimiento, correo, estado, municipio, parroquia, comuna, nivel_academico, discapacidad) 
                      VALUES 
                      (:tipo_identificacion, :identificacion, :nombres, :apellidos, :telefono, :sexo, :fecha_nacimiento, :correo, :estado, :municipio, :parroquia, :comuna, :nivel_academico, :discapacidad)";
            
            $stmt = $this->conn->prepare($query);
            $this->bindParams($stmt, $data);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Participante registrado exitosamente."];
            }
            return ["success" => false, "mensaje" => "No se pudo registrar el participante."];
        } catch (PDOException $e) {
            error_log("Error al registrar el participante: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al registrar el participante."];
        }
    }

    // Obtener participante por ID
    public function obtener($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_participante = :id_participante";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_participante', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return ["success" => true, "data" => $data];
            }
            return ["success" => false, "mensaje" => "Participante no encontrado."];
        } catch (PDOException $e) {
            error_log("Error al obtener el participante: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al obtener el participante."];
        }
    }

    // Editar participante
    public function editar($id, $data)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET 
                      tipo_identificacion = :tipo_identificacion,
                      identificacion = :identificacion,
                      nombres = :nombres,
                      apellidos = :apellidos,
                      telefono = :telefono,
                      sexo = :sexo,
                      fecha_nacimiento = :fecha_nacimiento,
                      correo = :correo,
                      estado = :estado,
                      municipio = :municipio,
                      parroquia = :parroquia,
                      comuna = :comuna,
                      nivel_academico = :nivel_academico,
                      discapacidad = :discapacidad
                      WHERE id_participante = :id_participante";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_participante', $id, PDO::PARAM_INT);
            $this->bindParams($stmt, $data);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Participante actualizado exitosamente."];
            }
            return ["success" => false, "mensaje" => "No se pudo actualizar el participante."];
        } catch (PDOException $e) {
            error_log("Error al editar el participante: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al editar el participante."];
        }
    }

    // Eliminar participante
    public function eliminar($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_participante = :id_participante";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_participante', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ["success" => true, "mensaje" => "Participante eliminado exitosamente."];
            }
            return ["success" => false, "mensaje" => "No se pudo eliminar el participante."];
        } catch (PDOException $e) {
            error_log("Error al eliminar el participante: " . $e->getMessage());
            return ["success" => false, "mensaje" => "Error al eliminar el participante."];
        }
    }

    // Método privado para vincular parámetros
    private function bindParams($stmt, $data)
    {
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    }
}
