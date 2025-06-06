<?php

namespace app\controllers;

use app\models\ActivitiesReportModel;

class ActivitiesReportController
{
    private $activitiesReportModel;

    public function __construct()
    {
        $this->activitiesReportModel = new ActivitiesReportModel();
    }

    public function handleRequestActivitiesReport()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'activitiesReport_create':
                $this->createReport();
                break;
            case 'activitiesReport_fetch_delete':
                $this->deleteReport();
                break;
            case 'activitiesReport_fetch_update':
                $this->updateReport();
                break;
            case 'activitiesReport_fetch_one':
                $this->fetchReport();
                break;
            case 'activitiesReport_fetch_page':
                $this->fetchReportsPage();
                break;
            case 'activitiesReport_upload_temp_files':
                $this->uploadTempFiles();
                break;
            case 'activitiesReport_delete_temp_file':
                $this->deleteTempFiles();
                break;
            case 'searchParticipants':
                $this->searchParticipantsAjax();
                break;
            case 'getImagesByReport':
                $this->getImagesByReportAjax();
                break;
            default:
                break;
        }
    }

    private function uploadTempFiles()
    {
        $tempUploadDir = '../tmp/'; // Asegúrate de que esta es la ruta correcta (la que ya verificamos que funciona)

        if (!empty($_FILES['file'])) { // 'file' es el paramName por defecto de Dropzone
            $file = $_FILES['file'];

            // Verificar si no hay errores en la subida
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Obtener la extensión del archivo original
                $originalFileName = $file['name'];
                $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                // Generar un nombre de archivo completamente nuevo y único
                // Puedes usar uniqid() para una cadena única
                $newFileName = uniqid() . '.' . $fileExtension;

                // Si quieres añadir un prefijo para identificarlos mejor (opcional)
                // $newFileName = 'img_' . uniqid() . '.' . $fileExtension;

                $targetFilePath = $tempUploadDir . $newFileName;

                // Mover el archivo subido al directorio temporal
                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    // Éxito: Enviar la respuesta JSON a Dropzone
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Archivo temporal subido con éxito.',
                        'filename' => $newFileName, // Importante: Envía el nuevo nombre generado
                        'temp_url' => str_replace('../', '', $targetFilePath) // Esto es para la URL de Dropzone si la usas para previsualizar
                    ]);
                    exit;
                } else {
                    // Error al mover el archivo
                    header('Content-Type: application/json');
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo temporal.']);
                    exit;
                }
            } else {
                // Error de subida (ej. archivo demasiado grande, tipo no permitido)
                $errorMessage = 'Error en la subida del archivo.';
                switch ($file['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'El archivo es demasiado grande.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'El archivo se subió parcialmente.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'No se seleccionó ningún archivo.';
                        break;
                        // Añadir más casos si necesitas manejar otros errores específicos
                }
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $errorMessage]);
                exit;
            }
        } else {
            // Si no se recibió ningún archivo o el nombre del campo no es 'file'
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se recibió ningún archivo válido.']);
            exit;
        }
    }
    private function deleteTempFiles()
    {
        // index.php?view=activitiesReport&action=activitiesReport_delete_temp_file

        $tempUploadDir = '../tmp/';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $filename = $input['filename'] ?? null;

            if ($filename) {
                $filePath = $tempUploadDir . $filename;
                if (file_exists($filePath)) {
                    if (unlink($filePath)) { // Eliminar el archivo
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Archivo temporal eliminado.']);
                        http_response_code(200);
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el archivo temporal.']);
                        http_response_code(500);
                    }
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Archivo temporal no encontrado (ya eliminado o nunca existió).']);
                    http_response_code(200); // Considerar éxito si el archivo ya no está
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Nombre de archivo no proporcionado.']);
                http_response_code(400);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            http_response_code(405);
        }
        exit;
    }

    /**
     * Elimina una imagen existente asociada a un reporte.
     * Se llama cuando el usuario elimina una imagen cargada en el modo de edición.
     */
    private function deleteReportImage()
    {
        // Asegúrate de que el request sea POST y contenga los datos necesarios
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $filename = $input['filename'] ?? null;
        $reportId = $input['reportId'] ?? null; // Necesitamos el ID del reporte para la ruta y DB

        if (!$filename || !$reportId) {
            echo json_encode(['success' => false, 'message' => 'Nombre de archivo o ID de reporte no proporcionado.']);
            exit;
        }

        $reportImagesDir = '../uploads/report_' . $reportId . '/'; // Ruta de la carpeta final
        $filePath = $reportImagesDir . $filename;

        // 1. Eliminar de la base de datos
        // Necesitas un método en tu modelo para eliminar la entrada de la imagen por nombre de archivo e ID de reporte
        $deletedFromDb = $this->activitiesReportModel->deleteImagesForReport($reportId);

        if (!$deletedFromDb) {
            // Podría ser que ya no existía en DB o hubo un error en la consulta
            error_log("No se pudo eliminar la imagen '{$filename}' del reporte '{$reportId}' de la base de datos.");
            // Aunque no se eliminó de la DB, intentar eliminar el archivo por si acaso
        }

        // 2. Eliminar el archivo físico del servidor
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo json_encode(['success' => true, 'message' => 'Imagen eliminada exitosamente.']);
            } else {
                // Logear error de permisos o si el archivo está siendo usado
                error_log("Error al eliminar el archivo físico: {$filePath}");
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el archivo físico.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Archivo no encontrado en el servidor.']);
        }
        exit;
    }

    private function createReport()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $titulo = $_POST['titulo_reporte'];
        $fecha_actividad = $_POST['fecha_actividad'];
        $descripcion = $_POST['contenido_reporte'];
        $id_tipo_actividad = $_POST['id_tipo_actividad'];
        $participantes = isset($_POST['participantes']) ? explode(',', $_POST['participantes']) : [];

        $all_success = false;
        $message = 'Error desconocido al crear el reporte.';
        $report_id = null;

        try {
            // Crear el reporte principal (ajusta el modelo si es necesario para guardar id_tipo_actividad)
            $this->activitiesReportModel->setData($id_usuario, $titulo, $fecha_actividad, $descripcion, $id_tipo_actividad);
            $report_id = $this->activitiesReportModel->create();

            if (!$report_id) {
                $message = 'Error al crear el reporte principal en la base de datos.';
                throw new \Exception($message);
            }

            // Guardar participantes
            if (empty($participantes)) {
                $message = 'Debe agregar al menos un participante.';
                throw new \Exception($message);
            }
            if (count($participantes) > 4) {
                $message = 'No puede haber más de 4 participantes.';
                throw new \Exception($message);
            }
            $this->activitiesReportModel->addParticipantsToReport($report_id, $participantes);

            // --- LÓGICA PARA ARCHIVOS ---
            $tempUploadDir = '../tmp/'; // Ajusta si es necesario
            $baseUploadDir = 'uploads/'; // Ajusta si es necesario

            // Validaciones de archivos subidos (ya están bien)
            $uploadedTempFilesJson = $_POST['uploaded_temp_files'] ?? '[]';
            $uploadedTempFiles = json_decode($uploadedTempFilesJson, true);

            $maxAllowedFiles = 5; // Asegúrate de que coincida con tu config de Dropzone
            if (!is_array($uploadedTempFiles) || count($uploadedTempFiles) === 0) {
                $message = 'Se requiere al menos una imagen para el reporte.';
                throw new \Exception($message);
            }
            if (count($uploadedTempFiles) > $maxAllowedFiles) {
                $message = "Se excede el número máximo de archivos permitidos ({$maxAllowedFiles}).";
                throw new \Exception($message);
            }

            // **PASO CLAVE: Definir y crear la subcarpeta específica para este reporte**
            $finalUploadDir = $baseUploadDir . 'report_' . $report_id . '/';

            if (!is_dir($finalUploadDir)) {
                if (!mkdir($finalUploadDir, 0755, true)) { // Usa 0755 o 0775
                    $message = "Error: No se pudo crear el directorio final para las imágenes: {$finalUploadDir}.";
                    throw new \Exception($message);
                }
            }

            $imagesProcessedSuccessfully = 0; // Contador de imágenes procesadas con éxito

            foreach ($uploadedTempFiles as $tempFileName) {
                $sourcePath = $tempUploadDir . $tempFileName;
                $destinationPath = $finalUploadDir . $tempFileName;
                $currentImageSuccess = false; // Bandera para cada imagen

                if (file_exists($sourcePath)) {
                    // Obtener tipo MIME y tamaño ANTES de mover
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $sourcePath);
                    finfo_close($finfo);
                    $fileSize = filesize($sourcePath);

                    // **REVISA ESTA RUTA DE ORIGEN Y LA RUTA DE DESTINO. ¡SON LAS CLAVES!**
                    error_log("Intentando mover: De '{$sourcePath}' a '{$destinationPath}'"); // Log detallado
                    if (rename($sourcePath, $destinationPath)) {
                        error_log("Movido exitosamente: {$destinationPath}");
                        $relativePathForDB = 'uploads/report_' . $report_id . '/' . $tempFileName;

                        $addImageResult = $this->activitiesReportModel->addEvidenceToReport(
                            $report_id,
                            $relativePathForDB,
                            $mimeType,
                            $fileSize
                        );

                        if ($addImageResult) {
                            $currentImageSuccess = true;
                            $imagesProcessedSuccessfully++;
                            error_log("Evidencia {$tempFileName} REGISTRADA en BD para reporte {$report_id}.");
                        } else {
                            error_log("ERROR: Fallo al registrar evidencia en BD para reporte ID: {$report_id}, archivo: {$tempFileName}. Se intentará revertir el movimiento.");
                            // Si falla el registro en BD, revertimos el movimiento del archivo
                            unlink($destinationPath); // Eliminar del destino final
                            $message .= " Fallo al registrar imagen {$tempFileName} en BD.";
                            // No lanzamos excepción aquí, para intentar procesar otras imágenes si es posible
                        }
                    } else {
                        error_log("ERROR: Fallo al MOVER archivo de {$sourcePath} a {$destinationPath}. Mensaje del sistema: " . (error_get_last()['message'] ?? 'Desconocido'));
                        $message .= " Fallo al mover imagen {$tempFileName}.";
                        // No lanzamos excepción aquí para intentar procesar otras imágenes si es posible
                    }
                } else {
                    error_log("ADVERTENCIA: Archivo temporal NO ENCONTRADO en {$sourcePath} para procesamiento. Puede que ya haya sido movido/borrado por otro proceso.");
                    $message .= " Imagen {$tempFileName} no encontrada en temporal.";
                    // No lanzamos excepción aquí, ya que podría ser una advertencia y no un error fatal
                }
            }

            // Si se procesó al menos una imagen con éxito, o si no había imágenes que procesar (aunque es obligatorio)
            // Y el reporte principal se creó
            if ($imagesProcessedSuccessfully > 0 || count($uploadedTempFiles) === 0) {
                // Si count($uploadedTempFiles) === 0 se validó arriba que es error, así que solo si > 0
                $all_success = true;
                $message = 'Reporte de actividades creado correctamente y imágenes procesadas.';
            } else {
                // Si el reporte se creó pero ninguna imagen pudo ser procesada, es un fallo parcial
                $message = 'Reporte creado, pero ninguna imagen pudo ser procesada correctamente.';
                // Opcional: Si quieres revertir el reporte si no hay imágenes, lo harías aquí
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            // Si el reporte principal no se creó ($report_id es null), no hay nada que limpiar.
            // Si el reporte principal SÍ se creó pero hubo un error de imágenes (catch del throw new Exception):
            // Se debe considerar revertir la creación del reporte principal (eliminar de la BD)
            // y limpiar las imágenes que sí se movieron (si las hubo).
            if ($report_id) {
                error_log("Reversión necesaria para reporte ID: {$report_id}. Razón: {$message}");
                // Lógica para revertir:
                // 1. Eliminar el registro del reporte de la tabla principal
                // $this->activitiesReportModel->deleteReport($report_id);
                // 2. Eliminar la carpeta y los archivos movidos (si hubo alguno)
                // deleteDirectory($finalUploadDir); // Necesitarías implementar deleteDirectory
                $message = 'Error crítico al procesar el reporte. El reporte se eliminó. ' . $message;
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $all_success,
            'message' => $message,
            'type' => 'create',
            'idReport' => $report_id // Esto puede ser null si $report_id no se generó
        ]);
        exit;
    }



    private function deleteReport()
    {
        $id = $_GET['id_reporte_actividades'];
        $result = $this->activitiesReportModel->delete($id);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reporte de actividades eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el reporte de actividades']);
        }
    }

    private function updateReport()
    {
        $reportId = $_POST['id_reporte_actividades'] ?? null;
        if (!$reportId) {
            echo json_encode(['success' => false, 'message' => 'ID de reporte no proporcionado.']);
            exit;
        }

        // --- VALIDACIÓN: Participantes ---
        $participantes = isset($_POST['participantes']) ? explode(',', $_POST['participantes']) : [];
        if (empty($participantes) || (count($participantes) === 1 && $participantes[0] === "")) {
            echo json_encode(['success' => false, 'message' => 'Debe agregar al menos un participante.']);
            exit;
        }
        if (count($participantes) > 4) {
            echo json_encode(['success' => false, 'message' => 'No puede haber más de 4 participantes.']);
            exit;
        }

        $reportData = [
            'titulo_reporte'    => $_POST['titulo_reporte'] ?? null,
            'contenido_reporte' => $_POST['contenido_reporte'] ?? null,
            // ... otros campos ...
        ];
        if (!$this->activitiesReportModel->update($reportId, $reportData)) {
            error_log("ADVERTENCIA: Fallo al actualizar los datos principales del reporte ID: {$reportId}");
        }

        // --- SOLO ACTUALIZA PARTICIPANTES SI HAY AL MENOS UNO ---
        $this->activitiesReportModel->deleteParticipantsFromReport($reportId);
        $this->activitiesReportModel->addParticipantsToReport($reportId, $participantes);

        // --- INICIO: Lógica de Sincronización de Imágenes ---

        $uploadedTempFilesJson = $_POST['uploaded_temp_files'] ?? '[]';
        $currentFrontendFiles = json_decode($uploadedTempFilesJson, true); // Nombres de archivo que el frontend quiere

        // Rutas físicas usando APP_ROOT_PATH (debes tenerla definida en index.php)
        $tempUploadDir = APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
        $finalReportImagesDirPhysical = APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'report_' . $reportId . DIRECTORY_SEPARATOR;
        $baseUrlForDbAndFrontend = 'uploads/report_' . $reportId . '/';

        error_log("DEBUG: Iniciando actualización de reporte ID: {$reportId}");
        error_log("DEBUG: JSON recibido del frontend: " . $uploadedTempFilesJson);
        error_log("DEBUG: Nombres de archivo que el frontend quiere: " . print_r($currentFrontendFiles, true));
        error_log("DEBUG: Ruta temporal (física): {$tempUploadDir}");
        error_log("DEBUG: Ruta final (física): {$finalReportImagesDirPhysical}");
        error_log("DEBUG: Ruta URL para DB/frontend: {$baseUrlForDbAndFrontend}");

        // Asegúrate de que la carpeta de destino final exista físicamente
        if (!is_dir($finalReportImagesDirPhysical)) {
            error_log("DEBUG: La carpeta final NO EXISTE: {$finalReportImagesDirPhysical}. Intentando crearla.");
            if (!mkdir($finalReportImagesDirPhysical, 0755, true)) {
                error_log("ERROR CRÍTICO: Fallo al crear la carpeta: {$finalReportImagesDirPhysical}. PERMISOS en " . dirname($finalReportImagesDirPhysical));
                echo json_encode(['success' => false, 'message' => 'Error al preparar el directorio de imágenes.']);
                exit;
            } else {
                error_log("DEBUG: Carpeta creada exitosamente: {$finalReportImagesDirPhysical}");
            }
        } else {
            error_log("DEBUG: La carpeta final YA EXISTE: {$finalReportImagesDirPhysical}");
        }

        // --- PASO 1: Obtener imágenes existentes de la DB ---
        // Necesitas un método en tu modelo que devuelva los nombres de archivo de las imágenes actuales.
        // Por ejemplo: getImagesForReport($reportId) que devuelve un array de 'nombre_archivo.jpg'.
        $existingDbImages = $this->activitiesReportModel->getImagesForReport($reportId); // Asegúrate de que este método exista y funcione.
        $existingDbFilenames = array_map(function($image) {
            // Asume que tu getImagesForReport devuelve objetos/arrays con una clave 'name' o similar
            // O extrae el nombre del archivo de la ruta completa si es lo que almacenas.
            $rutaCompleta = is_object($image) ? $image->ruta_evidencia : $image['ruta_evidencia'];
            
            return basename($rutaCompleta); 
        }, $existingDbImages);

        error_log("DEBUG: Im\xc3\xa1genes existentes en DB antes de sincronizar: " . print_r($existingDbFilenames, true));


        // --- PASO 2: Determinar qué eliminar y qué añadir ---
        $imagesToDelete = array_diff($existingDbFilenames, $currentFrontendFiles); // Están en DB, pero no en frontend (fueron eliminadas)
        $imagesToKeepOrAddNew = $currentFrontendFiles; // Estas son las que el frontend quiere

        error_log("DEBUG: Im\xc3\xa1genes a ELIMINAR (Frontend NO las quiere): " . print_r($imagesToDelete, true));
        error_log("DEBUG: Im\xc3\xa1genes a MANTENER/A\xc3\x91ADIR (Frontend S\xc3\xcd las quiere): " . print_r($imagesToKeepOrAddNew, true));


        // --- PASO 3: Eliminar selectivamente de la BD y del servidor ---
        foreach ($imagesToDelete as $filenameToDelete) {
            $fullPathToDeletePhysical = $finalReportImagesDirPhysical . $filenameToDelete;

            // Eliminar de la base de datos
            if (!$this->activitiesReportModel->deleteImageByNameForReport($reportId, $baseUrlForDbAndFrontend . $filenameToDelete)) { // Necesitas un método para borrar por nombre/ruta
                error_log("ADVERTENCIA: Fallo al eliminar entrada DB para {$filenameToDelete} de reporte ID: {$reportId}.");
            } else {
                error_log("DEBUG: Entrada DB eliminada para: {$filenameToDelete}");
            }

            // Eliminar archivo físico
            if (file_exists($fullPathToDeletePhysical) && is_file($fullPathToDeletePhysical)) {
                if (!unlink($fullPathToDeletePhysical)) {
                    error_log("ERROR: Fallo al eliminar archivo f\xc3\xadsico: {$fullPathToDeletePhysical}. PERMISOS.");
                } else {
                    error_log("DEBUG: Archivo f\xc3\xadsico eliminado: {$fullPathToDeletePhysical}");
                }
            } else {
                error_log("DEBUG: Archivo a eliminar f\xc3\xadsicamente no encontrado: {$fullPathToDeletePhysical}");
            }
        }


        // --- PASO 4: Mover nuevas imágenes y/o re-registrar las existentes (que se mantienen) ---
        $errorsProcessingImages = [];
        $processedImageCount = 0;

        foreach ($imagesToKeepOrAddNew as $filename) {
            $sourcePath = $tempUploadDir . $filename;
            $destinationPathPhysical = $finalReportImagesDirPhysical . $filename;
            $fullPathForDbAndFrontendWithFilename = $baseUrlForDbAndFrontend . $filename;

            error_log("DEBUG: Procesando imagen (KEEP/ADD): '{$filename}'. Origen: '{$sourcePath}', Destino F\xc3\xadsico: '{$destinationPathPhysical}', URL DB: '{$fullPathForDbAndFrontendWithFilename}'");

            $isFileInTemp = file_exists($sourcePath);
            $isFileInFinal = file_exists($destinationPathPhysical);

            error_log("DEBUG:   Existe en Temporal: " . ($isFileInTemp ? "S\xc3\x8D" : "NO"));
            error_log("DEBUG:   Existe en Final: " . ($isFileInFinal ? "S\xc3\x8D" : "NO"));

            if ($isFileInTemp) {
                // Es una imagen NUEVA que se subió al temporal
                error_log("DEBUG: Archivo '{$filename}' encontrado en temporal. Intentando MOVER a final.");
                if (rename($sourcePath, $destinationPathPhysical)) {
                    $imageSize = filesize($destinationPathPhysical);
                    $imageMimeType = mime_content_type($destinationPathPhysical);

                    if (!$this->activitiesReportModel->addEvidenceToReport($reportId, $fullPathForDbAndFrontendWithFilename, $imageMimeType, $imageSize)) {
                        $errorsProcessingImages[] = "Fallo al registrar imagen en DB: {$filename}";
                        error_log("ERROR: Fallo al registrar NUEVA imagen {$filename} en DB. (Error de DB o clave duplicada)");
                    } else {
                        $processedImageCount++;
                        error_log("DEBUG: Imagen '{$filename}' MOVIDA Y REGISTRADA en DB exitosamente.");
                    }
                } else {
                    error_log("ERROR: Fallo al MOVER imagen de '{$sourcePath}' a '{$destinationPathPhysical}'. Posibles causas: permisos insuficientes en destino, archivo de origen no existe, o archivo bloqueado.");
                    $errorsProcessingImages[] = "Fallo al mover imagen de temporal a final: {$filename}";
                }
            } elseif ($isFileInFinal) {
                // Es una imagen EXISTENTE que el frontend decidió mantener.
                // Como NO ELIMINAMOS TODAS las entradas de la BD al principio, solo necesitamos re-insertar
                // si el archivo NO estaba ya en la base de datos (lo eliminamos selectivamente).
                // Si la estrategia es simplemente "mantener si existe físicamente y la DB no lo tiene",
                // necesitamos verificar si ya existe en DB antes de insertar.

                // Para evitar duplicados en la DB si el archivo se mantuvo y no fue borrado de la DB selectivamente
                $imageInDb = $this->activitiesReportModel->imageExistsInReport($reportId, $fullPathForDbAndFrontendWithFilename); // Necesitas este nuevo método

                if (!$imageInDb) { // Solo inserta si NO está ya en la DB
                    error_log("DEBUG: Archivo '{$filename}' encontrado en FINAL y NO en DB. Re-insertando en DB.");
                    $imageSize = filesize($destinationPathPhysical);
                    $imageMimeType = mime_content_type($destinationPathPhysical);

                    if (!$this->activitiesReportModel->addEvidenceToReport($reportId, $fullPathForDbAndFrontendWithFilename, $imageMimeType, $imageSize)) {
                        $errorsProcessingImages[] = "Fallo al re-registrar imagen existente en DB: {$filename}";
                        error_log("ERROR: Fallo al re-registrar imagen existente {$filename} en DB. (Posible error de DB o clave duplicada)");
                    } else {
                        $processedImageCount++;
                        error_log("DEBUG: Imagen existente '{$filename}' RE-REGISTRADA en DB exitosamente.");
                    }
                } else {
                    error_log("DEBUG: Imagen '{$filename}' ya existe en la DB y f\xc3\xadsicamente. No requiere acci\xc3\xb3n.");
                    $processedImageCount++; // Contar como procesada si ya existe
                }

            } else {
                error_log("ADVERTENCIA: Archivo '{$filename}' NO ENCONTRADO ni en temporal ni en final. (Es probable que el usuario la haya eliminado o haya un error en la lista del frontend).");
            }
        } // Fin del foreach imagesToKeepOrAddNew


        error_log("DEBUG: Resumen final de procesamiento: Im\xc3\xa1genes procesadas y registradas en DB: {$processedImageCount}. Errores encontrados: " . count($errorsProcessingImages));

        $response = [
            'success' => empty($errorsProcessingImages),
            'message' => empty($errorsProcessingImages) ? 'Reporte y imágenes actualizados con éxito.' : 'Reporte actualizado con errores en imágenes.',
            'errors'  => $errorsProcessingImages,
            'type'    => 'edit',
        ];

        echo json_encode($response);
    }





    private function fetchReport()
    {
        $id = $_GET['id_reporte_actividades'];
        $report = $this->activitiesReportModel->readOne($id);

        header('Content-Type: application/json');
        if ($report) {
            $images = $this->activitiesReportModel->getImagesByReportId($id);
            $participants = $this->activitiesReportModel->getParticipantsByReport($id);

            // Nombre de la categoría y el id_tipo_actividad normalizado
            $categoria = '';
            $id_tipo_actividad = null;
            if (isset($report['id_tipo_reporte']) || isset($report['id_tipo_actividad'])) {
                $id_tipo_actividad = $report['id_tipo_reporte'] ?? $report['id_tipo_actividad'];
                $categoria = $this->activitiesReportModel->getTipoActividadNombre($id_tipo_actividad);
            }

            $existingImages = [];
            if (is_array($images)) {
                foreach ($images as $img) {
                    $existingImages[] = [
                        'name' => $img['name'],
                        'size' => $img['size'],
                        'type' => $img['type']
                    ];
                }
            }

            $reportData = [
                'id_reporte_actividades' => $report['id_reporte_actividades'],
                'fecha_actividad'        => $report['fecha_actividad'],
                'titulo_reporte'         => $report['titulo_reporte'],
                'contenido_reporte'      => $report['contenido_reporte'],
                'categoria'              => $categoria,
                'id_tipo_actividad'      => $id_tipo_actividad,
                'existingImages'         => $existingImages,
                'participants'           => $participants
            ];

            echo json_encode($reportData);
        } else {
            echo json_encode(['error' => 'Reporte de actividades no encontrado']);
        }
    }

    private function fetchReportsPage()
    {
        $reports = $this->activitiesReportModel->readPage();

        // Agregar participantes, nombre de categoría y id_tipo_actividad a cada reporte
        if (
            $reports && is_array($reports)
        ) {
            foreach ($reports as &$report) {
                // Participantes
                $participants = $this->activitiesReportModel->getParticipantsByReport($report['id_reporte_actividades']);
                if (is_array($participants)) {
                    $report['participantes'] = array_map(function ($p) {
                        if (is_array($p) && isset($p['cedula']) && isset($p['nombre'])) {
                            return $p['cedula'] . ' - ' . $p['nombre'];
                        }
                        return $p;
                    }, $participants);
                } else {
                    $report['participantes'] = [];
                }
                // Normalizar id_tipo_actividad y obtener nombre de la categoría
                if (isset($report['id_tipo_reporte']) || isset($report['id_tipo_actividad'])) {
                    $id_tipo = $report['id_tipo_reporte'] ?? $report['id_tipo_actividad'];
                    $report['id_tipo_actividad'] = $id_tipo; // Siempre incluirlo con este nombre
                    $report['categoria'] = $this->activitiesReportModel->getTipoActividadNombre($id_tipo);
                } else {
                    $report['id_tipo_actividad'] = null;
                    $report['categoria'] = null;
                }
            }
            unset($report);
        }

        header('Content-Type: application/json');
        if ($reports) {
            echo json_encode($reports);
        } else {
            echo json_encode(['error' => 'No se encontraron reportes de actividades']);
        }
    }

    public function searchParticipantsAjax()
    {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit;
        }
        $results = $this->activitiesReportModel->searchParticipants($q);
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * Devuelve solo los nombres de las imágenes asociadas a un reporte (para el PDF)
     */
    private function getImagesByReportAjax()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$id) {
            echo json_encode([]);
            exit;
        }
        $images = $this->activitiesReportModel->getImagesForReport($id); // Devuelve array con 'ruta_evidencia'
        // Solo devolver el nombre del archivo (última parte de la ruta)
        $result = array_map(function($img) {
            if (isset($img['ruta_evidencia'])) {
                return $img['ruta_evidencia'];
            }
            return null;
        }, $images);
        // Filtrar nulos
        $result = array_filter($result);
        echo json_encode(array_values($result));
        exit;
    }
}
