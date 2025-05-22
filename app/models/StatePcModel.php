<?php
namespace app\models;

use app\config\DataBase;

class StatePcModel // <-- Cambiado de DepartmentModel a StatePcModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    // Debes tener el método getStates, no getDepartments
    public function getStates()
    {
        try {
            $sql = "SELECT * FROM estado_equipo_informatico"; // Cambia el nombre de la tabla si es necesario
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los estados: " . $e->getMessage());
        }
    }
}
?>