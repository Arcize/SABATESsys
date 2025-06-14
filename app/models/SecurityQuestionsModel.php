<?php

namespace app\models;

use app\config\DataBase;

class SecurityQuestionsModel
{
    private $db;
    private $cedula;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }
    public function createSecurityQuestions($id_usuario, $data)
    {
        try {
            foreach ($data as $question) {
                $sql = "INSERT INTO usuario_pregunta (id_usuario, id_pregunta, respuesta) VALUES (:id_usuario,:id_pregunta, :respuesta)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->bindParam(':id_pregunta', $question['id_pregunta']);
                $stmt->bindParam(':respuesta', $question['respuesta']);
                $stmt->execute();
            }
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getUserSecurityQuestions($cedula)
    {
        try {
            $sql = "SELECT ps.id_pregunta, ps.texto_pregunta
                    FROM persona p
                    JOIN usuario u ON p.id_usuario = u.id_usuario
                    JOIN usuario_pregunta up ON u.id_usuario = up.id_usuario
                    JOIN preguntas_seguridad ps ON up.id_pregunta = ps.id_pregunta
                    WHERE p.cedula = :cedula;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getSecurityQuestions()
    {
        try {
            $sql = "SELECT * FROM preguntas_seguridad";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function verifySecurityAnswer($cedula, $id_pregunta, $respuesta)
    {

        try {
            // Obtener el id_usuario a partir de la cedula
            $sql = "SELECT u.id_usuario 
                    FROM persona p
                    JOIN usuario u ON p.id_usuario = u.id_usuario
                    WHERE p.cedula = :cedula";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                return false; // Cedula no encontrada
            }

            $id_usuario = $user['id_usuario'];
            $sql = "SELECT respuesta FROM usuario_pregunta WHERE id_usuario = :id_usuario AND id_pregunta = :id_pregunta";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_pregunta', $id_pregunta);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result && password_verify($respuesta, $result['respuesta'])) {
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getUserQuestionsBySession($id_usuario)
    {
        try {
            $sql = "SELECT ps.id_pregunta, ps.texto_pregunta, up.respuesta
                    FROM usuario_pregunta up
                    JOIN preguntas_seguridad ps ON up.id_pregunta = ps.id_pregunta
                    WHERE up.id_usuario = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            // No devolver el hash de la respuesta, solo la pregunta y un campo vacío para respuesta
            return array_map(function($row) {
                return [
                    'id_pregunta' => $row['id_pregunta'],
                    'pregunta' => $row['texto_pregunta'],
                    'respuesta' => '' // El usuario debe rellenar
                ];
            }, $rows);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function updateUserQuestions($id_usuario, $preguntas)
    {
        try {
            // Eliminar preguntas previas
            $sql = "DELETE FROM usuario_pregunta WHERE id_usuario = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            // Insertar nuevas preguntas y respuestas
            foreach ($preguntas as $pregunta) {
                $hash = password_hash($pregunta['respuesta'], PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuario_pregunta (id_usuario, id_pregunta, respuesta) VALUES (:id_usuario, :id_pregunta, :respuesta)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->bindParam(':id_pregunta', $pregunta['id_pregunta']);
                $stmt->bindParam(':respuesta', $hash);
                $stmt->execute();
            }
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
