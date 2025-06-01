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
        $PCs = $this->pcModel->readPage();
        
        $customSort = [
            "id_equipo_informatico",
            "fabricante_equipo_informatico",
            "estado_equipo_informatico",
            "nombre_completo",
            "fabricante_procesador_nombre",
            "nombre_procesador",
            "motherboard",
            "fuente",
            "capacidad_ram_total",
            "almacenamiento_total",
        ];
        if ($PCs) {
            foreach ($PCs as &$PC) {
                $newSort = [];
                foreach ($customSort as $key) {
                    if (isset($PC[$key])) {
                        $newSort[$key] = $PC[$key];
                    }
                }
                $PC = $newSort;
            }
            echo json_encode($PCs);
        } else {
            echo json_encode(['error' => 'No se encontraron reportes de fallas']);
        }
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

    /**
     * Devuelve todos los datos necesarios para el reporte de un equipo informático.
     * Entrada: POST id_equipo_informatico
     * Salida: JSON con todos los datos del equipo (incluyendo módulos de RAM y almacenamiento)
     */
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
}
