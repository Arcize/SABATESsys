<?php
namespace app\controllers;

use app\models\ActivityTypeModel;

class ActivityTypeController
{
    private $model;

    public function __construct()
    {
        $this->model = new ActivityTypeModel();
    }

    public function listTypes()
    {
        try {
            $types = $this->model->getTypes();
            return $types;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
