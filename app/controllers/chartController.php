<?php
include_once(__DIR__ . '/../models/chartModel.php');

class chartController
{
    private $chartModel;

    public function __construct()
    {
        $this->chartModel = new chartModel();
    }


    public function chartData()
    {
        try {
            $data = $this->chartModel->getData();
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>