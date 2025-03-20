<?php
include_once("app/models/employeeModel.php");

class employeeController
{
    private $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new employeeModel();
    }

    public function handleRequestEmployee()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'employee_create':
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
                    $this->createEmployee();
                }
                break;
            case 'employee_delete':
                $this->deleteEmployee();
                break;
            case 'employee_update':
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
                    $this->updateEmployee();
                }
                break;
            case 'employee_edit':
                $this->editEmployee();
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
        $departamento = $_POST['departamento'];
        $sexo = $_POST['sexo'];
        $fecha_nac = $_POST['fecha_nac'];

        $this->employeeModel->getData($nombre, $apellido, $cedula, $correo, $departamento, $sexo, $fecha_nac);
        $this->employeeModel->create();

        header("Location: index.php?view=employeeTable");
        exit();
    }

    private function deleteEmployee()
    {
        $id = $_GET['id'];
        $this->employeeModel->delete($id);

        header("Location: index.php?view=employeeTable");
        exit();
    }

    private function updateEmployee()
    {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $correo = $_POST['correo'];
        $departamento = $_POST['departamento'];
        $sexo = $_POST['sexo'];
        $fecha_nac = $_POST['fecha_nac'];

        $this->employeeModel->getData($nombre, $apellido, $cedula, $correo, $departamento, $sexo, $fecha_nac);
        $this->employeeModel->update($id);

        header("Location: index.php?view=employeeTable");
        exit();
    }

    private function editEmployee()
    {
        $id = $_GET['id'];
        $employee = $this->employeeModel->readOne($id);
    }


}

?>