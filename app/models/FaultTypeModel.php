<?php
namespace app\models;
use app\config\DataBase;

class FaultTypeModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getFaultTypes()
    {
        try {
            $sql = "SELECT * FROM tipo_falla;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los tipos de fallas: " . $e->getMessage());
        }
    }
}
?>