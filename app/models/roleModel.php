<?php
include_once("app\models\DB.php");

class roleModel
{
    private $db;
    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }
    public function readAll()
    {
        try {
            $sql = "SELECT * FROM rol";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
