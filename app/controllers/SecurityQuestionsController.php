<?php

namespace app\controllers;

use app\models\SecurityQuestionsModel;

class SecurityQuestionsController
{
    private $model;

    public function __construct()
    {
        $this->model = new SecurityQuestionsModel();
    }

    public function handleRequestSecurityQuestions()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            switch ($action) {
                case 'securityQuestions_fetch_create':
                    $this->createSecurityQuestions();
                    break;
                case 'securityQuestions_fetch_list_user':
                    $this->listUserSecurityQuestions();
                    break;
                case 'validateAnswers':
                    $this->verifySecurityQuestions();
                    break;
                default:
                    echo "Acción no válida.";
                    break;
            }
        } else {
            echo "No se ha especificado ninguna acción.";
        }
    }
    public function createSecurityQuestions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Transformar los datos en un array de arrays
            $hashAnswer1 = password_hash($_POST['securityAnswer-1'], PASSWORD_DEFAULT);
            $hashAnswer2 = password_hash($_POST['securityAnswer-2'], PASSWORD_DEFAULT);
            $hashAnswer3 = password_hash($_POST['securityAnswer-3'], PASSWORD_DEFAULT);
            $data = [
                ['id_pregunta' => $_POST['securityQuestion-1'], 'respuesta' => $hashAnswer1],
                ['id_pregunta' => $_POST['securityQuestion-2'], 'respuesta' => $hashAnswer2],
                ['id_pregunta' => $_POST['securityQuestion-3'], 'respuesta' => $hashAnswer3],
            ];

            $id_usuario = $_SESSION['id_usuario'];
            $result = $this->model->createSecurityQuestions($id_usuario, $data);

            if ($result) {
                error_log("Preguntas de seguridad registradas para el usuario: " . $id_usuario);
                unset($_SESSION['securityQuestions']);
                header("Location: index.php?view=dashboard");
                exit();
            } else {
                error_log("Error al registrar preguntas de seguridad para el usuario: " . $id_usuario);
            }
        } else {
            error_log("Método no permitido para crear preguntas de seguridad.");
        }
    }

    public function listUserSecurityQuestions()
    {

        $cedula = $_POST['cedula'];
        try {
            $questions = $this->model->getUserSecurityQuestions($cedula);

            $response = [
                "success" => count($questions) > 0, // Será true si hay preguntas, false si no
                "questions" => $questions
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function verifySecurityQuestions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answer = $_POST['securityAnswer'];
            $cedula = $_POST['cedula'];
            $id_pregunta = $_POST['questionId'];
            $result = $this->model->verifySecurityAnswer($cedula, $id_pregunta, $answer);
            header('Content-Type: application/json');
            if ($result) {
                $response = [
                    "success" => true,
                    "message" => "Respuesta correcta."
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Respuesta incorrecta."
                ];
            }
            echo json_encode($response);
            
        } else {
            echo "Método no permitido para verificar preguntas de seguridad.";
        }
    }

    public function listSecurityQuestions()
    {
        try {
            $questions = $this->model->getSecurityQuestions();
            return $questions;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
