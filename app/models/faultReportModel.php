<?php

namespace app\models;

use app\config\DataBase;


class FaultReportModel
{
    private $id_usuario;
    private $id_equipo_informatico;
    private $contenido_reporte_fallas;
    private $fecha_falla;
    private $id_tipo_falla;
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function setData($id_usuario, $id_equipo_informatico, $contenido_reporte_fallas, $fecha_falla, $id_tipo_falla)
    {
        $this->id_usuario = $id_usuario;
        $this->id_equipo_informatico = $id_equipo_informatico;
        $this->contenido_reporte_fallas = $contenido_reporte_fallas;
        $this->fecha_falla = $fecha_falla;
        $this->id_tipo_falla = $id_tipo_falla;
    }

    function generateUniqueReportCode($length = 8)
    {
        do {
            $code = strtoupper(bin2hex(random_bytes($length / 2))); // Genera código aleatorio
            $exists = $this->checkCodeExistsInDatabase($code); // Verifica en la BD
        } while ($exists); // Repite hasta generar un código único

        return $code;
    }

    function checkCodeExistsInDatabase($code)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reporte_fallas WHERE codigo_reporte_fallas = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Retorna true si el código ya existe
    }

    public function create()
    {
        try {
            $this->db->beginTransaction();
            $codigo = $this->generateUniqueReportCode();
            $sql = "INSERT INTO reporte_fallas (codigo_reporte_fallas, id_usuario, id_equipo_informatico, contenido_reporte_fallas, id_estado_reporte_fallas, prioridad, fecha_falla, id_tipo_falla) 
                    VALUES (:codigo, :id_usuario, :id_equipo_informatico, :contenido_reporte_fallas, 1, 'Baja', :fecha_falla, :id_tipo_falla)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->bindParam(':id_equipo_informatico', $this->id_equipo_informatico);
            $stmt->bindParam(':contenido_reporte_fallas', $this->contenido_reporte_fallas);
            $stmt->bindParam(':fecha_falla', $this->fecha_falla);
            $stmt->bindParam(':id_tipo_falla', $this->id_tipo_falla);
            $stmt->execute();
            $lastInsertId = $this->db->lastInsertId();
            $this->db->commit();
            return ['success' => true, 'id' => $lastInsertId];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            return ['success' => false, 'id' => null];
        }
    }

    public function readOne($id, $type)
    {
        try {
            // Consulta principal del reporte
            $sql = "SELECT fr.*, p.*, tf.tipo_falla, erf.estado_reporte_fallas, d.nombre_departamento 
                    FROM reporte_fallas fr
                    JOIN persona p on fr.id_usuario = p.id_usuario
                    JOIN tipo_falla tf on fr.id_tipo_falla = tf.id_tipo_falla
                    JOIN estado_reporte_fallas erf on fr.id_estado_reporte_fallas = erf.id_estado_reporte_fallas
                    JOIN departamento d on p.id_departamento = d.id_departamento
                    WHERE fr.id_reporte_fallas = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $reporte = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$reporte) {
                return null;
            }

            // Consulta de todos los seguimientos asociados con nombres descriptivos
            $sqlSeguimiento = "SELECT 
        s.*, 
        p.nombre AS nombre_usuario_accion, 
        p.apellido AS apellido_usuario_accion,
        erf.estado_reporte_fallas AS nombre_estado_reporte,
        t.nombre AS nombre_tecnico,
        t.apellido AS apellido_tecnico
    FROM seguimiento s
    LEFT JOIN usuario u ON s.id_usuario_accion = u.id_usuario
    LEFT JOIN persona p ON u.id_usuario = p.id_usuario
    LEFT JOIN estado_reporte_fallas erf ON s.id_estado_reporte = erf.id_estado_reporte_fallas
    LEFT JOIN usuario ut ON s.id_tecnico = ut.id_usuario
    LEFT JOIN persona t ON ut.id_usuario = t.id_usuario
    WHERE s.id_reporte_fallas = :id
    ORDER BY s.fecha_seguimiento ASC";
            $stmtSeguimiento = $this->db->prepare($sqlSeguimiento);
            $stmtSeguimiento->bindParam(':id', $id);
            $stmtSeguimiento->execute();
            $seguimientos = $stmtSeguimiento->fetchAll(\PDO::FETCH_ASSOC);

            // Añadir los seguimientos al array del reporte
            $reporte['seguimiento'] = $seguimientos;

            return $reporte;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function readOneById($id_reporte_fallas)
    {
        $sql = "SELECT * FROM reporte_fallas WHERE id_reporte_fallas = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_reporte_fallas]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function readPage()
    {
        try {
            $sql = "SELECT fr.*, 
       CONCAT(p1.nombre, ' ', p1.apellido) AS usuario_reportante, 
       CONCAT(p2.nombre, ' ', p2.apellido) AS tecnico_asignado_nombre, 
       erf.estado_reporte_fallas, 
       frt.tipo_falla
FROM reporte_fallas fr
JOIN usuario u1 ON fr.id_usuario = u1.id_usuario  -- Relación usuario que reportó la falla
JOIN persona p1 ON u1.id_usuario = p1.id_usuario  -- Obtener nombre completo desde persona
LEFT JOIN usuario u2 ON fr.tecnico_asignado = u2.id_usuario  -- Relación técnico asignado
LEFT JOIN persona p2 ON u2.id_usuario = p2.id_usuario  -- Obtener nombre completo del técnico
JOIN tipo_falla frt ON fr.id_tipo_falla = frt.id_tipo_falla
JOIN estado_reporte_fallas erf ON fr.id_estado_reporte_fallas = erf.id_estado_reporte_fallas
ORDER BY fr.id_reporte_fallas DESC
";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // --- NUEVO: Obtener solo los reportes del usuario actual ---
    public function readPageByUser($id_usuario)
    {
        try {
            $sql = "SELECT fr.*, 
       CONCAT(p1.nombre, ' ', p1.apellido) AS usuario_reportante, 
       CONCAT(p2.nombre, ' ', p2.apellido) AS tecnico_asignado_nombre, 
       erf.estado_reporte_fallas, 
       frt.tipo_falla
FROM reporte_fallas fr
JOIN usuario u1 ON fr.id_usuario = u1.id_usuario
JOIN persona p1 ON u1.id_usuario = p1.id_usuario
LEFT JOIN usuario u2 ON fr.tecnico_asignado = u2.id_usuario
LEFT JOIN persona p2 ON u2.id_usuario = p2.id_usuario
JOIN tipo_falla frt ON fr.id_tipo_falla = frt.id_tipo_falla
JOIN estado_reporte_fallas erf ON fr.id_estado_reporte_fallas = erf.id_estado_reporte_fallas
WHERE fr.id_usuario = :id_usuario
ORDER BY fr.id_reporte_fallas DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, \PDO::PARAM_INT);
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
            $sql = "UPDATE reporte_fallas SET id_usuario = :id_usuario, id_equipo_informatico = :id_equipo_informatico, contenido_reporte_fallas = :contenido_reporte_fallas, fecha_falla = :fecha_falla WHERE id_reporte_fallas = :id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->bindParam(':id_equipo_informatico', $this->id_equipo_informatico);
            $stmt->bindParam(':contenido_reporte_fallas', $this->contenido_reporte_fallas);
            $stmt->bindParam(':fecha_falla', $this->fecha_falla);
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

    public function updateTechnician($reportId, $technicianId, $state)
    {
        try {
            $this->db->beginTransaction();
            $sql = "UPDATE reporte_fallas SET tecnico_asignado = :technicianId WHERE id_reporte_fallas = :reportId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':technicianId', $technicianId, \PDO::PARAM_INT);
            $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
            $stmt->execute();

            $sql = "UPDATE reporte_fallas SET id_estado_reporte_fallas = :state WHERE id_reporte_fallas = :reportId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':state', $state, \PDO::PARAM_INT);
            $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
            $stmt->execute();
            $this->db->commit();
            return true; // Retorna true si se actualizó correctamente
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function deleteTechnician($reportId, $state = 1)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE reporte_fallas SET tecnico_asignado = NULL WHERE id_reporte_fallas = :reportId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
            $stmt->execute();

            $sql = "UPDATE reporte_fallas SET id_estado_reporte_fallas = :state WHERE id_reporte_fallas = :reportId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':state', $state, \PDO::PARAM_INT);
            $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
            $stmt->execute();
            $this->db->commit();
            return true; // Retorna true si se actualizó correctamente
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function createTracking($id_reporte_fallas, $accion = null, $id_usuario_accion, $nullTecnico = false, $descripcion = null)
    {
        try {
            // Consultar el reporte para obtener los datos necesarios
            $sql = "SELECT id_usuario, id_estado_reporte_fallas, tecnico_asignado, prioridad FROM reporte_fallas WHERE id_reporte_fallas = :id_reporte_fallas";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_reporte_fallas', $id_reporte_fallas, \PDO::PARAM_INT);
            $stmt->execute();
            $reporte = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$reporte) {
                throw new \Exception("No se encontró el reporte con ID $id_reporte_fallas");
            }

            $accion = $accion ?? 'Creación de reporte';
            $usuario_id = $_SESSION['id_usuario'] ?? $id_usuario_accion; // Usar el ID del usuario de la sesión o el proporcionado
            $id_estado = $reporte['id_estado_reporte_fallas'];
            $id_tecnico = $nullTecnico ? null : (!empty($reporte['tecnico_asignado']) ? $reporte['tecnico_asignado'] : null);
            $prioridad = $reporte['prioridad'];

            // Si es creación de reporte, insertar prioridad 'Baja'
            if ($accion === 'Creación de reporte') {
                $sql = "INSERT INTO seguimiento 
                (id_reporte_fallas, accion, id_usuario_accion, id_estado_reporte, id_tecnico, prioridad) 
                VALUES (:id_reporte_fallas, :accion, :usuario_id, :id_estado, :id_tecnico, :prioridad)";
                $stmt = $this->db->prepare($sql);
                $prioridad = 'Baja';
                $stmt->bindParam(':prioridad', $prioridad, \PDO::PARAM_STR);
            } else if ($descripcion !== null) {
                $sql = "INSERT INTO seguimiento 
                (id_reporte_fallas, accion, id_usuario_accion, id_estado_reporte, id_tecnico, prioridad, descripcion) 
                VALUES (:id_reporte_fallas, :accion, :usuario_id, :id_estado, :id_tecnico, :prioridad, :descripcion)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':descripcion', $descripcion, \PDO::PARAM_STR);
            } else {
                $sql = "INSERT INTO seguimiento 
                (id_reporte_fallas, accion, id_usuario_accion, id_estado_reporte, id_tecnico, prioridad) 
                VALUES (:id_reporte_fallas, :accion, :usuario_id, :id_estado, :id_tecnico, :prioridad)";
                $stmt = $this->db->prepare($sql);
            }

            $stmt->bindParam(':id_reporte_fallas', $id_reporte_fallas, \PDO::PARAM_INT);
            $stmt->bindParam(':accion', $accion, \PDO::PARAM_STR);
            $stmt->bindParam(':usuario_id', $usuario_id, \PDO::PARAM_INT);
            $stmt->bindParam(':id_estado', $id_estado, \PDO::PARAM_INT);
            $stmt->bindParam(':prioridad', $prioridad, \PDO::PARAM_STR);
            // Si el técnico es null, bindearlo como NULL
            if ($id_tecnico !== null) {
                $stmt->bindParam(':id_tecnico', $id_tecnico, \PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':id_tecnico', null, \PDO::PARAM_NULL);
            }

            return $stmt->execute();
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Obtener el estado actual de un reporte
    public function getReportStatus($reportId)
    {
        $sql = "SELECT id_estado_reporte_fallas FROM reporte_fallas WHERE id_reporte_fallas = :reportId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_estado_reporte_fallas'] : null;
    }

    public function attendReport($reportId, $state = 3)
    {
        try {
            $this->db->beginTransaction();
            $sql = "UPDATE reporte_fallas SET id_estado_reporte_fallas = :state WHERE id_reporte_fallas = :reportId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':state', $state, \PDO::PARAM_INT);
            $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
            $stmt->execute();
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    // Actualiza el estado del reporte de fallas (para invalidación por admin)
    public function updateReportStatus($reportId, $estado)
    {
        try {
            $this->db->beginTransaction();
            $sql = "UPDATE reporte_fallas SET id_estado_reporte_fallas = :estado WHERE id_reporte_fallas = :reportId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':estado', $estado, \PDO::PARAM_INT);
            $stmt->bindParam(':reportId', $reportId, \PDO::PARAM_INT);
            $stmt->execute();
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
