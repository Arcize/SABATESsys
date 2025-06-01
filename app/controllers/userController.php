<?php

namespace app\controllers;

use app\models\UserModel;
use app\models\RoleModel;
use app\models\NotificacionModel;

class UserController
{
    private $userModel;
    private $roleModel;
    private $notificacionModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->roleModel = new RoleModel();
        $this->notificacionModel = new NotificacionModel();
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
            case 'update_password':
                $this->updatePassword();
                break;
            case 'dashboard_config':
                $this->dashboardSetup();
                break;
            case 'get_profile':
                $this->get_profile();
                break;
            case 'update_email':
                $this->update_email();
                break;
            default:
                break;
        }
    }

    private function dashboardSetup()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userId = $_SESSION['id_usuario'];
            // Leer el cuerpo de la petición (JSON)
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Si solo envías la configuración:
            $config = json_encode($data);

            // Guardar la configuración en la base de datos
            $result = $this->userModel->saveDashboardConfig($userId, $config);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Configuración guardada correctamente']);
            } else {
                echo json_encode(['error' => 'Error al guardar la configuración']);
            }
        } else {
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
    public function getDashboardConfig()
    {
        $userId = $_SESSION['id_usuario'];
        $config = $this->userModel->getDashboardConfig($userId);
        return $config !== false ? $config : null;
    }
    private function updatePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cedula = $_POST['cedula'];
            if (!isset($cedula) || empty($cedula)) {
                echo json_encode(['error' => 'Cédula requerida']);
                return;
            }
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($newPassword !== $confirmPassword) {
                echo json_encode(['error' => 'Las contraseñas no coinciden']);
                return;
            }

            $hashedPassword = $this->userModel->passwordHash($newPassword);
            $result = $this->userModel->updatePassword($cedula, $hashedPassword);

            // --- Notificación al admin si la cédula existe y la contraseña fue actualizada ---
            if ($result) {
                // Buscar el rol de administrador
                $roles = $this->roleModel->getRoles();
                $id_rol_admin = null;
                foreach ($roles as $rol) {
                    if (stripos($rol['rol'], 'admin') !== false) {
                        $id_rol_admin = $rol['id_rol'];
                    }
                }
                if ($id_rol_admin) {
                    $mensaje = 'Solicitud de reinicio de contraseña para el usuario con cédula: ' . $cedula;
                    $this->notificacionModel->crear($mensaje, 'rol', $id_rol_admin, null);
                }
                echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
            } else {
                echo json_encode(['error' => 'Error al actualizar la contraseña']);
            }
        } else {
            echo json_encode(['error' => 'Método no permitido']);
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
        $users = $this->userModel->readPage();
        if ($users) {
            $data = [];
            foreach ($users as $user) {
                $rowData = $user;
                // Define aquí las opciones para el rol. Esto podría venir de una tabla de roles.
                $rolesOptions = $this->userModel->getAllRoles(); // Asume que tienes esta función en tu modelo

                // Modifica la columna 'rol' para incluir la información del acordeón
                $rowData['rol'] = [
                    'id' => $user['id_rol'], // Asume que la columna en tu tabla se llama 'rol'
                    'value' => $user['rol'], // Asume que la columna en tu tabla se llama 'rol'
                    "rol_renderAs" => "accordion",
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
            $password = $_POST['password'];
            if ($password && $id) {
                if ($this->userModel->isAnEmployee($id)) {
                    $hashedPassword = $this->userModel->passwordHash($password);
                    $this->userModel->setData($hashedPassword);
                    $idLast = $this->userModel->register();
                    $this->userModel->updatePersonIdUser($idLast, $id);
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
    public function isSecurityQuestionsSetup()
    {
        $userId = $_SESSION["id_usuario"];
        return $this->userModel->isSecurityQuestionsSetup($userId);
    }
    public function hasPermission($permissionName)
    {

        $userId = $_SESSION["id_usuario"];
        return $this->userModel->hasPermission($userId, $permissionName);
    }
    public function get_profile()
    {
        // Obtener el id_usuario de la sesión
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }
        // Usar EmployeeModel para obtener los datos básicos
        $employeeModel = new \app\models\EmployeeModel();
        $user = $employeeModel->getProfile($id_usuario);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el usuario']);
        }
    }

    public function update_email()
    {
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }
        $correo = $_POST['correo'] ?? null;
        if (!$correo) {
            echo json_encode(['success' => false, 'message' => 'Correo no proporcionado']);
            return;
        }
        $employeeModel = new \app\models\EmployeeModel();
        $result = $employeeModel->updateEmail($id_usuario, $correo);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Correo actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el correo']);
        }
    }
}
