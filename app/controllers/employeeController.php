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
        $action = isset($_GET['action']) ? $_GET['action'] : '';

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
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Página actual
        $recordsPerPage = isset($_GET['recordsPerPage']) ? $_GET['recordsPerPage'] : 10; // Número de registros por página
        $employee = $this->employeeModel->readPage($page, $recordsPerPage);
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
}
