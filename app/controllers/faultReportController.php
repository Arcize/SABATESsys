<?php

namespace app\controllers;

use app\models\FaultReportModel;
use app\models\RoleModel;
use app\models\NotificacionModel;

class FaultReportController
{
    private $faultReportsModel;
    private $roleModel;
    private $notificacionModel;

    public function __construct()
    {
        $this->faultReportsModel = new FaultReportModel();
        $this->roleModel = new RoleModel();
        $this->notificacionModel = new NotificacionModel();
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
            case 'attend_report':
                $this->attendReport();
                break;
            case 'invalidate_report':
                $this->invalidateReport();
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

            // Obtener el código del reporte de falla
            $reporte = $this->faultReportsModel->readOneById($id_reporte_fallas);
            $codigo_reporte = $reporte && isset($reporte['codigo_reporte_fallas']) ? $reporte['codigo_reporte_fallas'] : $id_reporte_fallas;

            // --- Notificaciones ---
            // Obtener roles de técnicos y admin (incluyendo el rol 1)
            $roles = $this->roleModel->getRoles();
            $adminRole = [
                'id_rol' => 1,
                'rol' => 'Administrador'
            ];
            $roles[] = $adminRole;
            $id_rol_tecnico = null;
            $id_rol_admin = null;
            foreach ($roles as $rol) {
                if (isset($rol['rol']) && $rol['rol'] === 'Técnico') {
                    $id_rol_tecnico = $rol['id_rol'];
                }
                if (isset($rol['rol']) && $rol['rol'] === 'Administrador') {
                    $id_rol_admin = $rol['id_rol'];
                }
            }
            // Notificación para técnicos (rol)
            if ($id_rol_tecnico) {
                $mensaje = 'Nuevo reporte de falla creado con el código: #' . $codigo_reporte;
                $this->notificacionModel->crear($mensaje, 'rol', $id_rol_tecnico, $id_reporte_fallas);
            }
            // Notificación para administrador (rol)
            if ($id_rol_admin) {
                $mensaje = 'Nuevo reporte de falla creado con el código: #' . $codigo_reporte;
                $this->notificacionModel->crear($mensaje, 'rol', $id_rol_admin, $id_reporte_fallas);
            }
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
        // Si el admin asigna, el id viene por POST como tecnico_id, si no, se toma de la sesión
        $isAdminAssign = isset($_POST['tecnico_id']);
        $technicianId = $isAdminAssign ? $_POST['tecnico_id'] : (isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null);
        $reportId = isset($_POST['report_id']) ? $_POST['report_id'] : null;
        $state = 2; // Estado 2 significa "En Proceso"

        if (!$technicianId || !$reportId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes para asignar técnico']);
            return;
        }
        // Validar estado
        $currentStatus = $this->faultReportsModel->getReportStatus($reportId);
        if ($currentStatus === 3) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se puede asignar un técnico a un reporte completado.']);
            return;
        }
        $result = $this->faultReportsModel->updateTechnician($reportId, $technicianId, $state);
        header('Content-Type: application/json');
        if ($result) {
            $mensajeSeguimiento = $isAdminAssign ? 'Reporte asignado por el administrador' : 'Reporte aceptado por el técnico';
            $this->faultReportsModel->createTracking($reportId, $mensajeSeguimiento, $technicianId);
            echo json_encode(['success' => true, 'message' => 'Técnico asignado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al asignar técnico']);
        }
    }
    private function unassignTechnician()
    {
        $reportId = $_POST['id_reporte_fallas'];
        $descripcion = isset($_POST['observacion']) ? $_POST['observacion'] : null;
        if (!$reportId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes para asignar técnico']);
            return;
        }
        // Validar estado
        $currentStatus = $this->faultReportsModel->getReportStatus($reportId);
        if ($currentStatus === 3) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se puede rechazar un reporte completado.']);
            return;
        }
        $id_usuario = $_SESSION['id_usuario'];
        $result = $this->faultReportsModel->deleteTechnician($reportId, $state = 1);
        $this->faultReportsModel->createTracking($reportId, 'Reporte rechazado', $id_usuario, true, $descripcion);
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Ha rechazado el reporte correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al rechazar el reporte']);
        }
    }
    private function attendReport()
    {
        $reportId = $_POST['id_reporte_fallas'];
        $descripcion = isset($_POST['observacion']) ? $_POST['observacion'] : null;
        $id_usuario = $_SESSION['id_usuario'];
        if (!$reportId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes para atender el reporte']);
            return;
        }
        // Validar estado
        $currentStatus = $this->faultReportsModel->getReportStatus($reportId);
        if ($currentStatus === 3) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se puede atender un reporte completado.']);
            return;
        }
        // Cambiar estado a "Completado" (3)
        $result = $this->faultReportsModel->attendReport($reportId, 3);
        $this->faultReportsModel->createTracking($reportId, 'Reporte atendido', $id_usuario, false, $descripcion);
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reporte atendido correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al atender el reporte']);
        }
    }
    private function invalidateReport()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }
        $reportId = isset($_POST['id_reporte_fallas']) ? $_POST['id_reporte_fallas'] : null;
        $reason = isset($_POST['invalid_reason']) ? $_POST['invalid_reason'] : null;
        $observacion = isset($_POST['invalid_observacion']) ? $_POST['invalid_observacion'] : null;
        if (!$reportId || !$reason) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes para invalidar el reporte']);
            return;
        }
        // Validar que el reporte NO tenga técnico asignado
        $reporte = $this->faultReportsModel->readOneById($reportId);
        if ($reporte && !empty($reporte['tecnico_asignado'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se puede invalidar un reporte que ya tiene un técnico asignado.']);
            return;
        }
        // Determinar estado según motivo
        $estado = ($reason === 'Duplicidad') ? 4 : (($reason === 'Inconsistencia') ? 5 : null);
        if (!$estado) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Motivo inválido']);
            return;
        }
        $result = $this->faultReportsModel->updateReportStatus($reportId, $estado);
        if ($result) {
            $this->faultReportsModel->createTracking($reportId, 'Reporte invalidado: ' . $reason, $_SESSION['id_usuario'], false, $observacion);
            echo json_encode(['success' => true, 'message' => 'Reporte invalidado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al invalidar el reporte']);
        }
    }
}
