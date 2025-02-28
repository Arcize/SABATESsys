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
        $marca = $_POST['marca'];
        $estado = $_POST['estado'];
        $persona_id = $_POST['persona_id'];
        $marca_procesador = $_POST['marca_procesador'];
        $nombre_procesador = $_POST['nombre_procesador'];
        $nucleos = $_POST['nucleos'];
        $frecuencia_procesador = $_POST['frecuencia_procesador'];
        $marca_motherboard = $_POST['marca_motherboard'];
        $modelo_motherboard = $_POST['modelo_motherboard'];
        $marca_fuente = $_POST['marca_fuente'];
        $wattage_fuente = $_POST['wattage_fuente'];
        $marca_ram = $_POST['marca_ram'];
        $tipo_ram = $_POST['tipo_ram'];
        $frecuencia_ram = $_POST['frecuencia_ram'];
        $capacidad_ram = $_POST['capacidad_ram'];

        // Mensajes de depuración
        error_log("Datos recibidos para crear PC:");
        error_log("Marca: $marca");
        error_log("Estado: $estado");
        error_log("Persona ID: $persona_id");
        error_log("Marca Procesador: $marca_procesador");
        error_log("Nombre Procesador: $nombre_procesador");
        error_log("Núcleos: $nucleos");
        error_log("Frecuencia Procesador: $frecuencia_procesador");
        error_log("Marca Motherboard: $marca_motherboard");
        error_log("Modelo Motherboard: $modelo_motherboard");
        error_log("Marca Fuente: $marca_fuente");
        error_log("Wattage Fuente: $wattage_fuente");
        error_log("Marca RAM: $marca_ram");
        error_log("Tipo RAM: $tipo_ram");
        error_log("Frecuencia RAM: $frecuencia_ram");
        error_log("Capacidad RAM: $capacidad_ram");

        $this->pcModel->getData($marca, $estado, $persona_id, $marca_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $marca_motherboard, $modelo_motherboard, $marca_fuente, $wattage_fuente, $marca_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram);
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
        $marca = $_POST['marca'];
        $estado = $_POST['estado'];
        $persona_id = $_POST['persona_id'];
        $marca_procesador = $_POST['marca_procesador'];
        $nombre_procesador = $_POST['nombre_procesador'];
        $nucleos = $_POST['nucleos'];
        $frecuencia_procesador = $_POST['frecuencia_procesador'];
        $marca_motherboard = $_POST['marca_motherboard'];
        $modelo_motherboard = $_POST['modelo_motherboard'];
        $marca_fuente = $_POST['marca_fuente'];
        $wattage_fuente = $_POST['wattage_fuente'];
        $marca_ram = $_POST['marca_ram'];
        $tipo_ram = $_POST['tipo_ram'];
        $frecuencia_ram = $_POST['frecuencia_ram'];
        $capacidad_ram = $_POST['capacidad_ram'];

        $this->pcModel->getData($marca, $estado, $persona_id, $marca_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $marca_motherboard, $modelo_motherboard, $marca_fuente, $wattage_fuente, $marca_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram);
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