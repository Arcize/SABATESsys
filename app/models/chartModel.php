<?php
include_once(__DIR__ . '/../models/DB.php');
class chartModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getData()
    {
        try {
            $sql = "SELECT CASE WHEN id_usuario IS NULL THEN 'Empleados No Registrados' 
                    ELSE 'Empleados Registrados' 
                    END AS estado, 
                    COUNT(*) AS total 
                    FROM persona 
                    GROUP BY estado;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($data, $row);
            }
            return $data;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los datos: " . $e->getMessage());
        }
    }
}
?>