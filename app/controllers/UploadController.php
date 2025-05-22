<?php

namespace app\controllers;

use app\models\FileUploader;

class UploadController
{
    public function handleUpload()
    {
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar si hay archivos antes de ejecutar la función del modelo
            if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
                echo json_encode(["message" => "No se adjuntaron archivos."]);
                exit;
            }
            
            // Obtener el ID del reporte de la solicitud
            $reportId = $_POST['idReport'] ?? null;
            // Si hay archivos, llamar al modelo
            $uploader = new FileUploader();
            $response = $uploader->uploadFiles($_FILES['files'], $reportId);

            echo json_encode(["message" => $response]);
            exit;
        }
    }
}
