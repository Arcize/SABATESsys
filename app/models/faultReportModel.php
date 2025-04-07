<?php
namespace app\models;
use app\config\DataBase;


class FaultReportModel
{
    private $id_usuario;
    private $id_equipo_informatico;
    private $contenido_reporte_fallas;
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function setData($id_usuario, $id_equipo_informatico, $contenido_reporte_fallas)
    {
        $this->id_usuario = $id_usuario;
        $this->id_equipo_informatico = $id_equipo_informatico;
        $this->contenido_reporte_fallas = $contenido_reporte_fallas;
    }

    public function create()
    {
        try {
            $sql = "INSERT INTO reporte_fallas (id_usuario, id_equipo_informatico, contenido_reporte_fallas, id_estado_reporte_fallas) 
                    VALUES (:id_usuario, :id_equipo_informatico, :contenido_reporte_fallas, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->bindParam(':id_equipo_informatico', $this->id_equipo_informatico);
            $stmt->bindParam(':contenido_reporte_fallas', $this->contenido_reporte_fallas);
            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function readOne($id)
    {
        try {
            $sql = "SELECT fr.*, p.cedula FROM reporte_fallas fr
                    JOIN persona p on fr.id_usuario = p.id_usuario
                    WHERE id_reporte_fallas = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function readPage($page, $recordsPerPage)
    {
        $offset = ($page - 1) * $recordsPerPage;
        try {
            $sql = "SELECT fr.*, u.username, erf.estado_reporte_fallas
                    FROM reporte_fallas fr
                    JOIN usuario u on fr.id_usuario = u.id_usuario
                    JOIN estado_reporte_fallas erf on fr.id_estado_reporte_fallas = erf.id_estado_reporte_fallas
                    ORDER BY id_reporte_fallas
                    LIMIT :recordsPerPage OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':recordsPerPage', $recordsPerPage, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getTotalRecords()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM reporte_fallas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public function update($id)
    {
        try {
            $sql = "UPDATE reporte_fallas SET id_usuario = :id_usuario, id_equipo_informatico = :id_equipo_informatico, contenido_reporte_fallas = :contenido_reporte_fallas WHERE id_reporte_fallas = :id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->bindParam(':id_equipo_informatico', $this->id_equipo_informatico);
            $stmt->bindParam(':contenido_reporte_fallas', $this->contenido_reporte_fallas);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM reporte_fallas WHERE id_reporte_fallas = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
