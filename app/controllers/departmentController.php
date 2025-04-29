<?php

namespace app\controllers;

use app\models\DepartmentModel;

class DepartmentController
{
    private $model;

    public function __construct()
    {
        $this->model = new DepartmentModel();
    }

    public function listDepartments()
    {
        try {
            $departments = $this->model->getDepartments();
            return $departments;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
