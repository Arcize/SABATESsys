<?php

namespace app\controllers;

use app\models\FaultReportModel;

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
            case 'faultReport_fetch_create':
                $this->createReport();
                break;
            case 'faultReport_fetch_delete':
                $this->deleteReport();
                break;
            case 'faultReport_fetch_update':
                $this->updateReport();
                break;
            case 'faultReport_fetch_page':
                $this->fetchReportsPage();
                break;
            case 'faultReport_fetch_total_records':
                $this->fetchTotalRecords();
                break;
            case 'assign_technician':
                $this->assignTechnician();
                break;
            case 'unassign_technician':
                $this->unassignTechnician();
                break;
            case 'generateReport':
                $this->generateReport();
                break;

            default:
                break;
        }
    }

    private function createReport()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $id_equipo_informatico = isset($_POST['id_equipo_informatico']) ? $_POST['id_equipo_informatico'] : null;
        $contenido_reporte_fallas = $_POST['contenido_reporte_fallas'];
        $fecha_falla = $_POST['fecha_falla'];
        $id_tipo_falla = $_POST['id_tipo_falla'];

        $this->faultReportsModel->setData($id_usuario, $id_equipo_informatico, $contenido_reporte_fallas, $fecha_falla, $id_tipo_falla);
        $result = $this->faultReportsModel->create();

        header('Content-Type: application/json');
        if ($result['success'] && $result['id']) {
            $id_reporte_fallas = $result['id'];
            $this->faultReportsModel->createTracking($id_reporte_fallas, null, $id_usuario);

            echo json_encode(['success' => true, 'message' => 'Reporte de fallas creado correctamente', 'type' => 'create']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el reporte de fallas']);
        }
    }
    private function deleteReport()
    {
        $id = $_GET['id_fault_report'];
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
        $id_usuario = $_SESSION['id_usuario'];
        $id_equipo_informatico = isset($_POST['id_equipo_informatico']) ? $_POST['id_equipo_informatico'] : null;
        $contenido_reporte_fallas = $_POST['contenido_reporte_fallas'];
        $fecha_falla = $_POST['fecha_falla'];
        $id_tipo_falla = $_POST['id_tipo_falla'];


        $this->faultReportsModel->setData($id_usuario, $id_equipo_informatico, $contenido_reporte_fallas, $fecha_falla, $id_tipo_falla);

        $result = $this->faultReportsModel->update($id);

        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reporte de fallas actualizado correctamente', 'type' => 'edit']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el reporte de fallas']);
        }
    }

    private function generateReport()
    {
        $id = $_POST['id_fault_report'];
        $type = $_POST['id_type_report'];
        $report = $this->faultReportsModel->readOne($id, $type);

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
        $reports = $this->faultReportsModel->readPage();

        // Retornar datos como JSON
        header('Content-Type: application/json');
        if ($reports) {
            echo json_encode($reports); // Enviamos el nuevo array
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

    private function assignTechnician()
    {
        $technicianId = $_SESSION['id_usuario']; // ID del técnico desde la sesión
        $reportId = $_POST['report_id']; // ID del reporte desde la solicitud
        $state = $_POST['state']; // Nuevo estado desde la solicitud

        if (!$technicianId || !$reportId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes para asignar técnico']);
            return;
        }

        $result = $this->faultReportsModel->updateTechnician($reportId, $technicianId, $state);

        // Retornar respuesta como JSON
        header('Content-Type: application/json');
        if ($result) {
            // Crear registro en la tabla seguimiento
            $this->faultReportsModel->createTracking($reportId, 'Reporte aceptado', $technicianId);

            echo json_encode(['success' => true, 'message' => 'Técnico asignado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al asignar técnico']);
        }
    }
    private function unassignTechnician()
    {
        $reportId = $_POST['report_id'];
        $state = $_POST['state'];

        if (!$reportId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes para asignar técnico']);
            return;
        }

        // Registrar en seguimiento el rechazo del técnico ANTES de quitar el técnico asignado
        $id_usuario = $_SESSION['id_usuario'];
        $result = $this->faultReportsModel->deleteTechnician($reportId, $state);
        $this->faultReportsModel->createTracking($reportId, 'Reporte Rechazado', $id_usuario, true);


        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Técnico rechazó el reporte correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al rechazar el reporte']);
        }
    }
}
