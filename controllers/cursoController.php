<?php
// cursoController.php
// Controlador para gestionar las acciones de los cursos

require_once __DIR__ . '/../models/Curso.php';

class CursoController
{
    /**
     * Listar todos los cursos.
     * Afecta: Tabla `curso`.
     * @return JSON Lista de cursos o mensaje de error.
     */
    public function listar()
    {
        try {
            $cursoModel = new Curso();
            $cursos = $cursoModel->listar();
            echo json_encode(['success' => true, 'data' => $cursos]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Error al listar los cursos: " . $e->getMessage()]);
        }
    }

    /**
     * Registrar un nuevo curso.
     * Afecta: Tabla `curso`.
     * @param array $data Datos del curso (nombre_curso, descripcion, id_tipo_curso).
     * @return JSON Mensaje de éxito o error.
     */
    public function registrar($data)
    {
        // Validaciones
        if (empty($data['nombre_curso']) || empty($data['descripcion']) || empty($data['id_tipo_curso'])) {
            echo json_encode(['success' => false, 'message' => 'El nombre, la descripción y el tipo de curso son requeridos.']);
            return;
        }

        try {
            $cursoModel = new Curso();
            $cursoModel->registrar($data['nombre_curso'], $data['descripcion'], $data['id_tipo_curso']);
            echo json_encode(['success' => true, 'message' => 'Curso registrado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Error al registrar el curso: " . $e->getMessage()]);
        }
    }

    /**
     * Obtener un curso por su ID.
     * Afecta: Tabla `curso`.
     * @param int $id ID del curso.
     * @return JSON Detalles del curso o mensaje de error.
     */
    public function obtener($id)
    {
        if (!is_numeric($id)) {
            echo json_encode(['success' => false, 'message' => 'El ID del curso debe ser un número.']);
            return;
        }

        try {
            $cursoModel = new Curso();
            $curso = $cursoModel->obtener($id);

            if ($curso) {
                echo json_encode(['success' => true, 'data' => $curso]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Curso no encontrado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Error al obtener el curso: " . $e->getMessage()]);
        }
    }

    /**
     * Aperturar un curso.
     * Afecta: Tabla `curso_apertura`.
     * @param int $id ID del curso.
     * @param array $data Datos de apertura (fecha_inicio, fecha_fin).
     * @return JSON Mensaje de éxito o error.
     */
    public function aperturarCurso($id, $data)
    {
        // Validaciones
        if (empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            echo json_encode(['success' => false, 'message' => 'Las fechas de inicio y fin son requeridas.']);
            return;
        }

        if (!strtotime($data['fecha_inicio']) || !strtotime($data['fecha_fin'])) {
            echo json_encode(['success' => false, 'message' => 'Formato de fecha inválido.']);
            return;
        }

        if (strtotime($data['fecha_inicio']) >= strtotime($data['fecha_fin'])) {
            echo json_encode(['success' => false, 'message' => 'La fecha de inicio debe ser anterior a la fecha de fin.']);
            return;
        }

        try {
            $cursoModel = new Curso();
            $cursoModel->aperturar($id, $data['fecha_inicio'], $data['fecha_fin']);
            echo json_encode(['success' => true, 'message' => 'Curso aperturado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Error al aperturar el curso: " . $e->getMessage()]);
        }
    }
}
