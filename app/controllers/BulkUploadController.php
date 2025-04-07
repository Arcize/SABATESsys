<?php

namespace app\controllers;

use app\models\BulkUploadModel;

class BulkUploadController
{
    private $BulkUploadModel;

    public function __construct()
    {
        $this->BulkUploadModel = new BulkUploadModel();
    }

    public function handleRequestBulkUpload()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'process_file':
                $this->processFile();
                break;
            default:
                echo json_encode(['error' => 'Invalid action']);
                break;
        }
    }

    private function processFile()
    {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['file']['name'];
            $tmp_name = $_FILES['file']['tmp_name'];
            $file_up_name = time() . $fileName;
            $uploadPath = '../tmp/' . $file_up_name; // Define una carpeta temporal

            // Crear la carpeta temporal si no existe
            if (!is_dir('temp_uploads')) {
                mkdir('temp_uploads', 0777, true);
            }

            if (move_uploaded_file($tmp_name, $uploadPath)) {
                // Archivo movido correctamente, ahora pasar la ruta al modelo
                $resultado = $this->BulkUploadModel->processExcel($uploadPath);

                if ($resultado === true) {
                    echo json_encode(['success' => 'Archivo Excel procesado correctamente.']);
                } else {
                    echo json_encode(['error' => 'Error al procesar el archivo Excel.']);
                    // O podrías enviar más detalles del error si el modelo los devuelve
                }

                // Opcional: Eliminar el archivo temporal después de procesarlo
                unlink($uploadPath);

            } else {
                echo json_encode(['error' => 'Error al mover el archivo subido al servidor.']);
            }
        } else {
            echo json_encode(['error' => 'No se subió ningún archivo o hubo un error en la subida.']);
            if (isset($_FILES['file'])) {
                echo json_encode(['upload_error_code' => $_FILES['file']['error']]);
            }
        }
    }
}