<?php
include_once("app/models/faultReportModel.php");

class FaultReportController
{
    private $faultReportsModel;

    public function __construct()
    {
        $this->faultReportsModel = new FaultReportModel();
    }

    public function handleRequestFaultReport()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'create_report':
                $this->createReport();
                break;
            case 'delete_report':
                $this->deleteReport();
                break;
            case 'update_report':
                $this->updateReport();
                break;
            case 'fetch_report':
                $this->fetchReport();
                break;
            case 'fetch_reports_page':
                $this->fetchReportsPage();
                break;
            case 'fetch_total_records':
                $this->fetchTotalRecords();
                break;
            default:
                break;
        }
    }

    private function createReport()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $id_equipo_informatico = $_POST['idPC'];
        $contenido_reporte_fallas = $_POST['contentFaultReport'];

        $this->faultReportsModel->setData($id_usuario, $id_equipo_informatico, $contenido_reporte_fallas);
        $result = $this->faultReportsModel->create();

        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reporte de fallas creado correctamente', 'type' => 'create']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el reporte de fallas']);
        }
    }

    private function deleteReport()
    {
        $id = $_GET['id_reporte_fallas'];
        $result = $this->faultReportsModel->delete($id);

        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reporte de fallas eliminado correctamente', 'type' => 'delete']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el reporte de fallas']);
        }
    }

    private function updateReport()
    {
        $id = $_POST['id_reporte_fallas'];
        $id_usuario = $_POST['id_usuario'];
        $id_equipo_informatico = $_POST['id_equipo_informatico'];
        $contenido_reporte_fallas = $_POST['contenido_reporte_fallas'];

        $this->faultReportsModel->setData($id_usuario, $id_equipo_informatico, $contenido_reporte_fallas);
        $result = $this->faultReportsModel->update($id);

        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reporte de fallas actualizado correctamente', 'type' => 'edit']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el reporte de fallas']);
        }
    }

    private function fetchReport()
    {
        $id = $_GET['id_reporte_fallas'];
        $report = $this->faultReportsModel->readOne($id);

        // Retornar datos como JSON
        header('Content-Type: application/json');
        if ($report) {
            echo json_encode($report);
        } else {
            echo json_encode(['error' => 'Reporte de fallas no encontrado']);
        }
    }

    private function fetchReportsPage()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Página actual
        $recordsPerPage = isset($_GET['recordsPerPage']) ? $_GET['recordsPerPage'] : 10; // Número de registros por página
        $reports = $this->faultReportsModel->readPage($page, $recordsPerPage);

        // Retornar datos como JSON
        header('Content-Type: application/json');
        if ($reports) {
            echo json_encode($reports);
        } else {
            echo json_encode(['error' => 'No se encontraron reportes de fallas']);
        }
    }

    private function fetchTotalRecords()
    {
        $totalRecords = $this->faultReportsModel->getTotalRecords();

        // Retornar datos como JSON
        header('Content-Type: application/json');
        if ($totalRecords) {
            echo json_encode($totalRecords);
        } else {
            echo json_encode(['error' => 'No se encontraron registros']);
        }
    }
}