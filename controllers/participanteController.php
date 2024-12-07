<?php
// participanteController.php
// Controlador para gestionar las acciones de los participantes

require_once __DIR__ . '/../models/Participante.php';

class ParticipanteController
{
    // Listar participantes
    public function listar()
    {
        try {
            $participanteModel = new Participante();
            $result = $participanteModel->listar();
            echo json_encode([
                "status" => "success",
                "data" => $result
            ]);
        } catch (Exception $e) {
            error_log("Error al listar los participantes: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Hubo un error al listar los participantes."
            ]);
        }
    }

    // Registrar un nuevo participante
    public function registrar($data)
    {
        // Validaciones básicas
        if (empty($data['identificacion']) || empty($data['nombres']) || empty($data['apellidos'])) {
            echo json_encode([
                "status" => "error",
                "message" => "La identificación, nombres y apellidos son requeridos."
            ]);
            return;
        }

        if (!empty($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "error",
                "message" => "El correo electrónico no es válido."
            ]);
            return;
        }

        try {
            $participanteModel = new Participante();
            if ($participanteModel->registrar($data)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Participante registrado exitosamente."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se pudo registrar el participante."
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al registrar el participante: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Hubo un error interno. Intenta de nuevo más tarde."
            ]);
        }
    }

    // Obtener un participante por ID
    public function obtener($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        try {
            $participanteModel = new Participante();
            $result = $participanteModel->obtener($id);
            if ($result) {
                echo json_encode([
                    "status" => "success",
                    "data" => $result
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "El participante no fue encontrado."
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al obtener el participante: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Hubo un error interno. Intenta de nuevo más tarde."
            ]);
        }
    }

    // Editar participante
    public function editar($id, $data)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if (empty($data['identificacion']) || empty($data['nombres']) || empty($data['apellidos'])) {
            echo json_encode([
                "status" => "error",
                "message" => "La identificación, nombres y apellidos son requeridos."
            ]);
            return;
        }

        if (!empty($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "error",
                "message" => "El correo electrónico no es válido."
            ]);
            return;
        }

        try {
            $participanteModel = new Participante();
            if ($participanteModel->editar($id, $data)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Participante actualizado exitosamente."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se pudo actualizar el participante."
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al editar el participante: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Hubo un error interno. Intenta de nuevo más tarde."
            ]);
        }
    }

    // Eliminar participante
    public function eliminar($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        try {
            $participanteModel = new Participante();
            if ($participanteModel->eliminar($id)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Participante eliminado exitosamente."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se pudo eliminar el participante."
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar el participante: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Hubo un error interno. Intenta de nuevo más tarde."
            ]);
        }
    }
}

