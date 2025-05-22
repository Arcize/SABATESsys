<?php

namespace app\models;

use app\config\Database;

class FileUploader
{
    private $baseUploadDir;
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    private $maxFileSize = 5 * 1024 * 1024;
    private $db;

    public function __construct($baseUploadDir = "../uploads/")
    {
        $this->baseUploadDir = realpath($baseUploadDir) ?: $baseUploadDir;
        $this->db = DataBase::getInstance();
    }

    public function uploadFiles($files, $reportId)
    {
        $responses = [];

        if (empty($files['name'][0])) {
            return ["No se subieron archivos."];
        }

        // Cambiar el nombre de la carpeta al ID del reporte
        $uploadDir = $this->baseUploadDir . DIRECTORY_SEPARATOR . "reporte_" . $reportId;

        // Crear carpeta si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($files['name'] as $key => $fileName) {
            $fileTmpName = $files['tmp_name'][$key];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validar extensión y tamaño
            if (!in_array($fileExtension, $this->allowedExtensions)) {
                $responses[] = "Error: Archivo no permitido ($fileName).";
                continue;
            }

            // Generar nombre único para el archivo
            $uniqueName = "archivo_" . uniqid() . "." . $fileExtension;
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . $uniqueName;

            // Mover archivo
            if (move_uploaded_file($fileTmpName, $filePath)) {
                // Guardar en la base de datos
                $relativePath = "reporte_" . $reportId . "/$uniqueName";
                $stmt = $this->db->prepare("INSERT INTO evidencia_reporte_actividades (id_actividad, ruta_evidencia) VALUES (?, ?)");
                $stmt->execute([$reportId, $relativePath]);

                $responses[] = "Archivo subido y guardado con report ID: $reportId";
            } else {
                $responses[] = "Error al subir el archivo: $fileName";
            }
        }

        return $responses;
    }
}
