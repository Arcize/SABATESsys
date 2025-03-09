<?php
include_once("app/models/pcModel.php");

class pcController
{
    private $pcModel;

    public function __construct()
    {
        $this->pcModel = new pcModel();
    }

    public function handleRequestPC()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'pc_create':
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $this->createPC();
                }
                break;
            case 'pc_delete':
                $this->deletePC();
                break;
            case 'pc_update':
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $this->updatePC();
                }
                break;
            case 'pc_edit':
                $this->editPC();
                break;
            default:
                break;
        }
    }

    private function createPC()
    {
        $fabricante = $_POST['fabricante'];
        $estado = $_POST['estado'];
        $persona_id = $_POST['persona_id'];
        $fabricante_procesador = $_POST['fabricante_procesador'];
        $nombre_procesador = $_POST['nombre_procesador'];
        $nucleos = $_POST['nucleos'];
        $frecuencia_procesador = $_POST['frecuencia_procesador'];
        $fabricante_motherboard = $_POST['fabricante_motherboard'];
        $modelo_motherboard = $_POST['modelo_motherboard'];
        $fabricante_fuente = $_POST['fabricante_fuente'];
        $wattage_fuente = $_POST['wattage_fuente'];
        $fabricante_ram = $_POST['fabricante_ram'];
        $tipo_ram = $_POST['tipo_ram'];
        $frecuencia_ram = $_POST['frecuencia_ram'];
        $capacidad_ram = $_POST['capacidad_ram'];
        $fabricante_almacenamiento = $_POST['fabricante_almacenamiento'];
        $tipo_almacenamiento = $_POST['tipo_almacenamiento'];
        $capacidad_almacenamiento = $_POST['capacidad_almacenamiento'];

        // Mensajes de depuración
        error_log("Datos recibidos para crear PC:");
        error_log("Fabricante: $fabricante");
        error_log("Estado: $estado");
        error_log("Persona ID: $persona_id");
        error_log("Fabricante Procesador: $fabricante_procesador");
        error_log("Nombre Procesador: $nombre_procesador");
        error_log("Núcleos: $nucleos");
        error_log("Frecuencia Procesador: $frecuencia_procesador");
        error_log("Fabricante Motherboard: $fabricante_motherboard");
        error_log("Modelo Motherboard: $modelo_motherboard");
        error_log("Fabricante Fuente: $fabricante_fuente");
        error_log("Wattage Fuente: $wattage_fuente");
        error_log("Fabricante RAM: $fabricante_ram");
        error_log("Tipo RAM: $tipo_ram");
        error_log("Frecuencia RAM: $frecuencia_ram");
        error_log("Capacidad RAM: $capacidad_ram");
        error_log("Fabricante Almacenamiento: $fabricante_almacenamiento");
        error_log("Tipo Almacenamiento: $tipo_almacenamiento");
        error_log("Capacidad Almacenamiento: $capacidad_almacenamiento");

        $this->pcModel->getData($fabricante, $estado, $persona_id, $fabricante_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $fabricante_motherboard, $modelo_motherboard, $fabricante_fuente, $wattage_fuente, $fabricante_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram, $fabricante_almacenamiento, $tipo_almacenamiento, $capacidad_almacenamiento);
        $this->pcModel->create();

        header("Location: index.php?view=pcTable");
        exit();
    }

    private function deletePC()
    {
        $id = $_GET['id'];
        $this->pcModel->delete($id);

        header("Location: index.php?view=pcTable");
        exit();
    }

    private function updatePC()
    {
        $id = $_POST['id'];
        $fabricante = $_POST['fabricante'];
        $estado = $_POST['estado'];
        $persona_id = $_POST['persona_id'];
        $fabricante_procesador = $_POST['fabricante_procesador'];
        $nombre_procesador = $_POST['nombre_procesador'];
        $nucleos = $_POST['nucleos'];
        $frecuencia_procesador = $_POST['frecuencia_procesador'];
        $fabricante_motherboard = $_POST['fabricante_motherboard'];
        $modelo_motherboard = $_POST['modelo_motherboard'];
        $fabricante_fuente = $_POST['fabricante_fuente'];
        $wattage_fuente = $_POST['wattage_fuente'];
        $fabricante_ram = $_POST['fabricante_ram'];
        $tipo_ram = $_POST['tipo_ram'];
        $frecuencia_ram = $_POST['frecuencia_ram'];
        $capacidad_ram = $_POST['capacidad_ram'];
        $fabricante_almacenamiento = $_POST['fabricante_almacenamiento'];
        $tipo_almacenamiento = $_POST['tipo_almacenamiento'];
        $capacidad_almacenamiento = $_POST['capacidad_almacenamiento'];

        $this->pcModel->getData($fabricante, $estado, $persona_id, $fabricante_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $fabricante_motherboard, $modelo_motherboard, $fabricante_fuente, $wattage_fuente, $fabricante_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram, $fabricante_almacenamiento, $tipo_almacenamiento, $capacidad_almacenamiento);
        $this->pcModel->update($id);

        header("Location: index.php?view=pcTable");
        exit();
    }

    private function editPC()
    {
        $id = $_GET['id'];
        $pc = $this->pcModel->readOne($id);
    }
}