<?php
namespace app\models;
use app\config\DataBase;

class ActivityTypeModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getTypes()
    {
        try {
            $sql = "SELECT id_tipo_actividad, tipo_actividad FROM tipo_actividad;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los tipos de actividad: " . $e->getMessage());
        }
    }
}
