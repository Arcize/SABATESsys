<?php

namespace app\controllers;

use app\models\PcModel;

class PcController
{
    private $pcModel;

    public function __construct()
    {
        $this->pcModel = new PcModel();
    }

    public function handleRequestPC()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'pc_fetch_create':
                $this->createPC();
                break;
            case 'pc_fetch_delete':
                $this->deletePC();
                break;
            case 'pc_fetch_update':
                $this->updatePC();
                break;
            case 'pc_fetch_one':
                $this->fetchPCOne();
                break;
            case 'pc_fetch_page':
                $this->fetchPcPage();
                break;
            case 'pc_fetch_deincorporated':
                $this->fetchPcDeincorporated();
                break;
            case 'pc_fetch_total_records':
                $this->fetchTotalRecords();
                break;
            case 'pc_fetch_id':
                $this->fetchPcId();
                break;
            case 'assign_pc':
                $this->assignPC();
                break;
            case 'unassign_pc':
                $this->unassignPC();
                break;
            case 'generateReport':
                $this->generateReport();
                break;
            case 'deincorporate_pc':
                $this->deincorporatePC();
                break;
            case 'reincorporate_pc':
                $this->reincorporatePC();
                break;
            case 'set_in_repair':
                $this->setInRepair();
                break;
            case 'set_operational':
                $this->setOperational();
                break;
            case 'set_broken':
                $this->setBroken();
                break;
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }
    }

    private function createPC()
    {
        $data = $_POST;
        $this->pcModel->getData(
            $data['fabricante_equipo_informatico'],
            $data['id_estado_equipo'],
            $data['fabricante_procesador'],
            $data['nombre_procesador'],
            $data['nucleos'],
            $data['frecuencia_procesador'],
            $data['fabricante_motherboard'],
            $data['modelo_motherboard'],
            $data['fabricante_fuente_poder'],
            $data['wattage_fuente'],
            $data['fabricante_ram'],
            $data['tipo_ram'], // string único
            $data['frecuencia_ram'],
            $data['capacidad_ram'],
            $data['fabricante_almacenamiento'],
            $data['tipo_almacenamiento'],
            $data['capacidad_almacenamiento']
        );
        $result = $this->pcModel->create();

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'PC creada correctamente', 'type' => 'create']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear la PC']);
        }
    }

    private function deletePC()
    {
        $id = $_GET['id_pc'];
        $result = $this->pcModel->delete($id);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'PC eliminada correctamente', 'type' => 'delete']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la PC']);
        }
    }

    private function updatePC()
    {
        $data = $_POST;
        $id = $data['id_equipo_informatico'];
        $this->pcModel->getData(
            $data['fabricante_equipo_informatico'],
            $data['id_estado_equipo'],
            $data['fabricante_procesador'],
            $data['nombre_procesador'],
            $data['nucleos'],
            $data['frecuencia_procesador'],
            $data['fabricante_motherboard'],
            $data['modelo_motherboard'],
            $data['fabricante_fuente_poder'],
            $data['wattage_fuente'],
            $data['fabricante_ram'],
            $data['tipo_ram'], // string único
            $data['frecuencia_ram'],
            $data['capacidad_ram'],
            $data['fabricante_almacenamiento'],
            $data['tipo_almacenamiento'],
            $data['capacidad_almacenamiento']
        );
        $result = $this->pcModel->update($id);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'PC actualizada correctamente', 'type' => 'edit']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la PC']);
        }
    }

    private function fetchPCOne()
    {
        $id = $_GET['id_equipo_informatico'];
        $pc = $this->pcModel->readOne($id);

        header('Content-Type: application/json');
        if ($pc) {
            echo json_encode($pc);
        } else {
            echo json_encode(['error' => 'PC no encontrada']);
        }
    }

    private function fetchPcPage()
    {
        // Traer todos los equipos MENOS los desincorporados (estado 4)
        $pcs = $this->pcModel->readAllExceptDeincorporated();
        header('Content-Type: application/json');
        echo json_encode($pcs);
    }
    private function fetchTotalRecords()
    {
        $totalRecords = $this->pcModel->getTotalRecords();
        if ($totalRecords) {
            // Retornar datos como JSON
            header('Content-Type: application/json');
            echo json_encode($totalRecords);
        } else {
            echo json_encode(['error' => 'No se encontraron registros']);
        }
    }
    private function fetchPcId()
    {
        $cedulaPC = $_GET['cedulaPC'];
        $pc = $this->pcModel->getPcId($cedulaPC);

        header('Content-Type: application/json');
        if ($pc) {
            echo json_encode($pc);
        } else {
            echo json_encode(['error' => 'PC no encontrada']);
        }
    }

    private function assignPC()
    {
        $id_equipo = $_POST['id_equipo_informatico'];
        $cedula = $_POST['cedula'];
        $result = $this->pcModel->assignToPerson($id_equipo, $cedula);
        header('Content-Type: application/json');
        if ($result === 'already_assigned') {
            echo json_encode(['success' => false, 'message' => 'Esta persona ya tiene un equipo asignado.']);
        } else if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo asignado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al asignar el equipo']);
        }
    }

    private function unassignPC()
    {
        $id_equipo = $_POST['id_equipo_informatico'];
        $result = $this->pcModel->unassignFromPerson($id_equipo);
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo desasignado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al desasignar el equipo']);
        }
    }

    
    private function generateReport()
    {
        $id = $_POST['id_equipo_informatico'] ?? null;
        if (!$id) {
            echo json_encode(['error' => 'ID de equipo no proporcionado']);
            return;
        }
        $pc = $this->pcModel->readOne($id);

        header('Content-Type: application/json');
        if ($pc) {
            echo json_encode($pc);
        } else {
            echo json_encode(['error' => 'Equipo no encontrado']);
        }
    }

    public function deincorporatePC()
    {
        $id = $_POST['id_equipo_informatico'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID de equipo no proporcionado']);
            return;
        }
        // Estado 4 = Desincorporado
        $result = $this->pcModel->updateState($id, 4);
        // Desasignar persona (id_persona = NULL)
        $this->pcModel->unassignFromPerson($id);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo desincorporado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo desincorporar el equipo']);
        }
    }

    public function reincorporatePC()
    {
        $id = $_POST['id_equipo_informatico'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID de equipo no proporcionado']);
            return;
        }
        // Estado 1 = Operativo (ajusta si tu tabla de estados usa otro valor para "Operativo")
        $result = $this->pcModel->updateState($id, 1);
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo reincorporado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo reincorporar el equipo']);
        }
    }

    public function fetchPcDeincorporated()
    {
        $pcs = $this->pcModel->readAllDeincorporated();
        header('Content-Type: application/json');
        echo json_encode($pcs);
    }

    public function setInRepair()
    {
        // Solo acepta peticiones POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $id_equipo = isset($_POST['id_equipo']) ? intval($_POST['id_equipo']) : 0;
        if (!$id_equipo) {
            echo json_encode(['success' => false, 'message' => 'ID de equipo inválido']);
            exit;
        }

        $pcModel = new \app\models\PcModel();
        // id_estado 3 = "En reparación"
        $result = $pcModel->updateEstado($id_equipo, 3);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo puesto en reparación']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado']);
        }
        exit;
    }

    public function setOperational()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        $id_equipo = isset($_POST['id_equipo']) ? intval($_POST['id_equipo']) : 0;
        if (!$id_equipo) {
            echo json_encode(['success' => false, 'message' => 'ID de equipo inválido']);
            exit;
        }
        $result = $this->pcModel->updateState($id_equipo, 1); // 1 = operativo
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo puesto en estado operativo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado']);
        }
        exit;
    }

    public function setBroken()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        $id_equipo = isset($_POST['id_equipo']) ? intval($_POST['id_equipo']) : 0;
        if (!$id_equipo) {
            echo json_encode(['success' => false, 'message' => 'ID de equipo inválido']);
            exit;
        }
        $result = $this->pcModel->updateState($id_equipo, 2); // 2 = averiado
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Equipo puesto en estado averiado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado']);
        }
        exit;
    }
}
