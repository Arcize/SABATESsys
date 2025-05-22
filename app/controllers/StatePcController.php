<?php

namespace app\controllers;

use app\models\StatePcModel;

class StatePcController
{
    private $model;

    public function __construct()
    {
        $this->model = new StatePcModel();
    }

    public function listStates()
    {
        try {
            $states = $this->model->getStates();
            return $states;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
