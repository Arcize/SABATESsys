<?php

namespace app\controllers;

use app\models\EmployeeModel;

class EmployeeController
{
    private $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    public function handleRequestEmployee()
    {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'employee_fetch_create':
                $this->createEmployee();
                break;
            case 'employee_fetch_delete':
                $this->deleteEmployee();
                break;
            case 'employee_fetch_update':
                $this->updateEmployee();
                break;
            case 'employee_fetch_one':
                $this->fetchEmployeeOne();
                break;
            case 'employee_fetch_page':
                $this->fetchEmployeePage();
                break;
            case 'employee_fetch_total_records':
                $this->fetchTotalRecords();
                break;
            case 'employee_fetch_cedula':
                $this->fetchCedula();
                break;
            case 'get_Cedula_By_Pc':
                $this->getCedulaByPc();
                break;
            case 'get_Cedula':
                $this->getCedula();
                break;
            case 'employee_deactivate':
                $this->deactivateEmployee();
                break;
            case 'employee_activate':
                $this->activateEmployee();
                break;
            case 'employee_check_status':
                $this->checkEmployeeStatus();
                break;
            case 'generateReport':
                $this->generateReport();
                break;
            case 'get_technicians':
                $this->getTechnicians();
                break;
            case 'get_profile':
                $this->getProfileBySession();
                break;
            case 'update_profile':
                $this->updateProfileBySession();
                break;
            case 'update_password':
                $this->updatePasswordBySession();
                break;
            case 'employee_fetch_inactive':
                $this->fetchEmployeeInactive();
                break;
            default:
                break;
        }
    }

    private function createEmployee()
    {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $correo = $_POST['correo'];
        $departamento = $_POST['id_departamento'];
        $sexo = $_POST['id_sexo'];
        $fecha_nac = $_POST['fecha_nac'];

        $this->employeeModel->getData($nombre, $apellido, $cedula, $correo, $departamento, $sexo, $fecha_nac);
        $result = $this->employeeModel->create();
        // Retornar respuesta como JSON
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Empleado actualizado correctamente', 'type' => 'create']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el empleado']);
        }
    }

    private function deleteEmployee()
    {
        $id = $_GET['id_persona'];
        $result = $this->employeeModel->delete($id);
        // Retornar respuesta como JSON
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Empleado actualizado correctamente', 'type' => 'delete']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el empleado']);
        }
    }

    private function updateEmployee()
    {
        $id = $_POST['id_persona'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $correo = $_POST['correo'];
        $departamento = $_POST['id_departamento'];
        $sexo = $_POST['id_sexo'];
        $fecha_nac = $_POST['fecha_nac'];

        $this->employeeModel->getData($nombre, $apellido, $cedula, $correo, $departamento, $sexo, $fecha_nac);
        $result = $this->employeeModel->update($id);

        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Empleado actualizado correctamente', 'type' => 'edit']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el empleado']);
        }
    }

    private function fetchEmployeeOne()
    {
        $id = $_GET['id_persona'];
        $employee = $this->employeeModel->readOne($id);
        if ($employee) {
            // Retornar datos como JSON
            header('Content-Type: application/json');
            echo json_encode($employee);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado']);
        }
    }
    private function fetchEmployeePage()
    {
        $employee = $this->employeeModel->readPage();
        if ($employee) {
            // Retornar datos como JSON
            header('Content-Type: application/json');
            echo json_encode($employee);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado']);
        }
    }
    private function fetchTotalRecords()
    {
        $totalRecords = $this->employeeModel->getTotalRecords();
        if ($totalRecords) {
            // Retornar datos como JSON
            header('Content-Type: application/json');
            echo json_encode($totalRecords);
        } else {
            echo json_encode(['error' => 'No se encontraron registros']);
        }
    }
    private function fetchCedula()
    {
        $cedula = $_GET['cedula'];
        $id_persona = $_GET['id_persona'];
        $exist = $this->employeeModel->verifyCedula($cedula, $id_persona);
        if ($exist) {
            header('Content-Type: application/json');
            echo json_encode(["exist" => $exist]);
        } else {
            echo json_encode(['error' => 'No se encontraron registros']);
        }
    }

    private function getCedula()
    {
        $cedula = $_GET['cedula'];
        $employee = $this->employeeModel->getCedula($cedula);
        // Retornar datos como JSON
        header('Content-Type: application/json');
        if ($employee) {
            echo json_encode(["exist" => $employee]);
        } else {
            echo json_encode(["error" => 'No se encontraron registros']);
        }
    }

    private function getCedulaByPc()
    {
        $idPC = $_GET['idPC'];
        $pc = $this->employeeModel->getCedulaPc($idPC);

        // Retornar datos como JSON
        header('Content-Type: application/json');
        if ($pc) {
            echo json_encode($pc);
        } else {
            echo json_encode(['error' => 'PC no encontrada']);
        }
    }

    private function deactivateEmployee()
    {
        $id = $_POST['id_persona'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado.']);
            exit;
        }
        // --- NO permitir desactivar usuario administrador ---
        // Busca el rol del usuario antes de desactivar
        $rol = $this->employeeModel->getRolByPersonaId($id);
        if ($rol && strtolower($rol) === 'administrador') {
            echo json_encode(['success' => false, 'message' => 'No se puede desactivar un usuario administrador.']);
            exit;
        }
        $result = $this->employeeModel->deactivate($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Empleado desactivado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo desactivar el empleado.']);
        }
        exit;
    }

    private function activateEmployee()
    {
        $id = $_POST['id_persona'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado.']);
            exit;
        }
        $result = $this->employeeModel->activate($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Empleado activado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo activar el empleado.']);
        }
        exit;
    }

    private function checkEmployeeStatus()
    {
        // Suponiendo que el id_persona está en la sesión
        $id = $_SESSION['id_usuario'] ?? null;
        if (!$id) {
            echo json_encode(['estado_empleado' => 'Inactivo']);
            exit;
        }
        $estado = $this->employeeModel->getEstadoEmpleado($id);
        echo json_encode(['estado_empleado' => $estado]);
        exit;
    }

    public function generateReport()
    {
        $id = $_POST['id_persona'] ?? null;
        if (!$id) {
            echo json_encode(['error' => 'ID no proporcionado']);
            exit;
        }
        $employee = $this->employeeModel->getEmployeeReportData($id);
        if ($employee) {
            echo json_encode($employee);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado']);
        }
        exit;
    }

    /**
     * Devuelve un array de técnicos (usuarios con rol 3)
     */
    public function getTechnicians()
    {
        // Usamos el modelo de usuario porque el rol está en la tabla usuario
        $userModel = new \app\models\UserModel();
        $tecnicos = [];
        $allUsers = $userModel->readPage();
        foreach ($allUsers as $u) {
            if (isset($u['id_rol']) && $u['id_rol'] == 3) {
                $tecnicos[] = [
                    'id_usuario' => $u['id_usuario'],
                    'nombre' => $u['nombre_completo']
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($tecnicos);
    }

    private function getProfileBySession()
    {
        // Obtener el id_usuario de la sesión
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }
        $user = $this->employeeModel->getProfile($id_usuario);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el usuario']);
        }
    }

    private function updateProfileBySession()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }
        // Validación de correo (igual que en formValidation.js)
        $correo = $_POST['correo'] ?? '';
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
            return;
        }
        // En el futuro aquí se pueden agregar más campos a actualizar
        $result = $this->employeeModel->updateEmail($id_usuario, $correo);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Correo actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el correo']);
        }
    }

    private function updatePasswordBySession()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        // Validaciones de formato
        if (strlen($new_password) < 8 ||
            !preg_match('/[A-Z]/', $new_password) ||
            !preg_match('/[a-z]/', $new_password) ||
            !preg_match('/[0-9]/', $new_password)) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.']);
            return;
        }
        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            return;
        }
        // Obtener el hash de la contraseña actual
        $userModel = new \app\models\UserModel();
        $user = $userModel->getUserByIdUsuario($id_usuario);
        if (!$user || !isset($user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            return;
        }
        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
            return;
        }
        // Actualizar la contraseña
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $result = $userModel->updatePasswordByIdUsuario($id_usuario, $hashedPassword);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la contraseña.']);
        }
    }

    private function fetchEmployeeInactive()
    {
        $employees = $this->employeeModel->readInactive();
        if ($employees) {
            header('Content-Type: application/json');
            echo json_encode($employees);
        } else {
            echo json_encode(['error' => 'No se encontraron empleados inactivos']);
        }
    }
}
