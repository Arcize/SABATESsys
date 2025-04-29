<?php

namespace app\controllers;

use app\models\RoleModel;

class RoleController
{
    private $model;

    public function __construct()
    {
        $this->model = new RoleModel();
    }

    public function listRoles()
    {
        try {
            $roles = $this->model->getRoles();
            return $roles;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
