<?php
include_once('app/models/departmentModel.php');

class departmentController
{
    private $model;

    public function __construct()
    {
        $this->model = new departmentModel();
    }

    public function listDepartments()
    {
        try {
            $departments = $this->model->getDepartments();
            return $departments;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

?>