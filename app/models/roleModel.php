<?php
namespace app\models;
use app\config\DataBase;


class RoleModel
{
    private $db;
    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }
    public function getRoles()
    {
        try {
            $sql = "SELECT * FROM rol WHERE id_rol != 1";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
