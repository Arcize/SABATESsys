<?php

namespace app\controllers;

use app\models\FaultTypeModel;

class FaultTypeController
{
    private $model;

    public function __construct()
    {
        $this->model = new FaultTypeModel();
    }

    public function listFaultTypes()
    {
        try {
            $faultTypes = $this->model->getFaultTypes();
            return $faultTypes;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}