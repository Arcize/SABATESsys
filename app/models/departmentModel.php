<?php
namespace app\models;
use app\config\DataBase;

class DepartmentModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getDepartments()
    {
        try {
            $sql = "SELECT * FROM departamento;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $data;
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los departamentos: " . $e->getMessage());
        }
    }
}
?>