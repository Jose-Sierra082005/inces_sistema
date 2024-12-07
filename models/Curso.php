<?php

// Curso.php
// Clase para gestionar los cursos

require_once 'Database.php';

class Curso
{
    private $conn;
    private $table_name = "curso";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Listar todos los cursos.
     * @return array|false Lista de cursos o false en caso de error.
     */
    public function listar()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar los cursos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar un nuevo curso.
     * @param string $nombre_curso Nombre del curso.
     * @param string $descripcion Descripción del curso.
     * @param int $id_tipo_curso ID del tipo de curso.
     * @return bool True si se registra correctamente, false si ocurre un error.
     */
    public function registrar($nombre_curso, $descripcion, $id_tipo_curso)
    {
        try {
            // Validar que los datos sean válidos
            if (empty($nombre_curso) || empty($descripcion) || !is_numeric($id_tipo_curso)) {
                error_log("Datos inválidos al intentar registrar un curso.");
                return false;
            }

            $query = "INSERT INTO " . $this->table_name . " (nombre_curso, descripcion, id_tipo_curso) 
                      VALUES (:nombre_curso, :descripcion, :id_tipo_curso)";
            $stmt = $this->conn->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(':nombre_curso', $nombre_curso);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_tipo_curso', $id_tipo_curso);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al registrar el curso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener un curso por su ID.
     * @param int $id ID del curso.
     * @return array|false Datos del curso o false en caso de error.
     */
    public function obtener($id)
    {
        try {
            // Validar que el ID sea numérico
            if (!is_numeric($id)) {
                error_log("ID inválido al intentar obtener un curso.");
                return false;
            }

            $query = "SELECT * FROM " . $this->table_name . " WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el curso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Aperturar un curso con fechas específicas.
     * @param int $id ID del curso.
     * @param string $fecha_inicio Fecha de inicio del curso.
     * @param string $fecha_fin Fecha de finalización del curso.
     * @return bool True si se actualiza correctamente, false en caso de error.
     */
    public function aperturar($id, $fecha_inicio, $fecha_fin)
    {
        try {
            // Validar datos
            if (!is_numeric($id) || !strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
                error_log("Datos inválidos al intentar aperturar un curso.");
                return false;
            }

            if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
                error_log("La fecha de inicio debe ser anterior a la fecha de fin.");
                return false;
            }

            $query = "UPDATE " . $this->table_name . " 
                      SET fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin 
                      WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->bindParam(':id_curso', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al aperturar el curso: " . $e->getMessage());
            return false;
        }
    }
}

