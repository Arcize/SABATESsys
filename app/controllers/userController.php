<?php

namespace app\controllers;

use app\models\UserModel;

class UserController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
    }
    public function handleRequestUser()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'user_fetch_page':
                $this->fetchUserPage();
                break;
            case 'user_fetch_total_records':
                $this->fetchTotalRecords();
                break;
            case 'user_fetch_one':
                $this->fetchUserOne();
                break;
            case 'user_fetch_update':
                $this->fetchUserUpdate();
                break;
            default:
                break;
        }
    }
    private function fetchUserOne()
    {
        $id = $_GET['id_usuario'];
        $user = $this->userModel->readOne($id);
        if ($user) {
            // Retornar datos como JSON
            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado']);
        }
    }
    private function fetchUserPage()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Página actual
        $recordsPerPage = isset($_GET['recordsPerPage']) ? $_GET['recordsPerPage'] : 10; // Número de registros por página
        $users = $this->userModel->readPage($page, $recordsPerPage);
        if ($users) {
            $data = [];
            foreach ($users as $user) {
                $rowData = $user;
                // Define aquí las opciones para el rol. Esto podría venir de una tabla de roles.
                $rolesOptions = $this->userModel->getAllRoles(); // Asume que tienes esta función en tu modelo

                // Modifica la columna 'rol' para incluir la información del acordeón
                $rowData['rol'] = [
                    'value' => $user['rol'], // Asume que la columna en tu tabla se llama 'rol'
                    "rol_renderAs"=> "accordion",
                    'options' => $rolesOptions
                ];
                $data[] = $rowData;
            }
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado']);
        }
    }
    public function fetchTotalRecords()
    {
        $totalRecords = $this->userModel->getTotalRecords();
        if ($totalRecords) {
            // Retornar datos como JSON
            header('Content-Type: application/json');
            echo json_encode($totalRecords);
        } else {
            echo json_encode(['error' => 'No se encontraron registros']);
        }
    }
    public function registerUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            if ($username && $password && $id) {
                if ($this->userModel->isAnEmployee($id)) {
                    $hashedPassword = $this->userModel->passwordHash($password);
                    $this->userModel->setData($username, $hashedPassword);
                    // $this->userModel->isAnAdmin($id);
                    $this->userModel->register();
                    $this->userModel->updatePersonIdUser($id);
                    exit();
                } else {
                    $_SESSION['register_failed'] = "No es un empleado";
                    header("Location: index.php?view=register");
                }
            } else {
                $_SESSION['register_failed'] = "Rellene todos los campos";
                echo "Input no válido";
                header("Location: index.php?view=register");
            }
        }
    }
    public function fetchUserUpdate()
    {
        $userId = $_GET["id_usuario"];
        $rolId = $_POST["id_rol"];
        $result = $this->userModel->updateRole($userId, $rolId);
        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente', 'type' => 'edit']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el empleado']);
        }
    }
    public function hasPermission($permissionName)
    {

        $userId = $_SESSION["id_usuario"];
        return $this->userModel->hasPermission($userId, $permissionName);
    }
}
