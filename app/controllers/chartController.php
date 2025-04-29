<?php
namespace app\controllers;

use app\models\ChartModel;

class ChartController
{
    private $chartModel;

    public function __construct()
    {
        $this->chartModel = new ChartModel();
    }

    public function chartsData()
    {
        try {
            $data = $this->chartModel->getChartsData();
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>