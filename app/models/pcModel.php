<?php
include_once("app\models\DB.php");

class pcModel
{
    private $marca;
    private $estado;
    private $persona_id;
    private $marca_procesador;
    private $nombre_procesador;
    private $nucleos;
    private $frecuencia_procesador;
    private $marca_motherboard;
    private $modelo_motherboard;
    private $marca_fuente;
    private $wattage_fuente;
    private $marca_ram;
    private $tipo_ram;
    private $frecuencia_ram;
    private $capacidad_ram;
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getData($marca, $estado, $persona_id, $marca_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $marca_motherboard, $modelo_motherboard, $marca_fuente, $wattage_fuente, $marca_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram)
    {
        $this->marca = $marca;
        $this->estado = $estado;
        $this->persona_id = $persona_id;
        $this->marca_procesador = $marca_procesador;
        $this->nombre_procesador = $nombre_procesador;
        $this->nucleos = $nucleos;
        $this->frecuencia_procesador = $frecuencia_procesador;
        $this->marca_motherboard = $marca_motherboard;
        $this->modelo_motherboard = $modelo_motherboard;
        $this->marca_fuente = $marca_fuente;
        $this->wattage_fuente = $wattage_fuente;
        $this->marca_ram = $marca_ram;
        $this->tipo_ram = $tipo_ram;
        $this->frecuencia_ram = $frecuencia_ram;
        $this->capacidad_ram = $capacidad_ram;
    }

    public function create()
    {
        try {
            $this->db->beginTransaction();

            // Insertar procesador
            $sql = "INSERT INTO procesador (marca_procesador, nombre_procesador, nucleos, frecuencia, id_estado_pieza_procesador) 
                    VALUES (:marca_procesador, :nombre_procesador, :nucleos, :frecuencia, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca_procesador', $this->marca_procesador);
            $stmt->bindParam(':nombre_procesador', $this->nombre_procesador);
            $stmt->bindParam(':nucleos', $this->nucleos);
            $stmt->bindParam(':frecuencia', $this->frecuencia_procesador);
            if (!$stmt->execute()) {
                throw new PDOException("Error executing query (procesador): " . implode(", ", $stmt->errorInfo()));
            }
            $id_procesador = $this->db->lastInsertId();

            // Insertar motherboard
            $sql = "INSERT INTO motherboard (modelo_motherboard, marca_motherboard, id_estado_pieza_motherboard) 
                    VALUES (:modelo_motherboard, :marca_motherboard, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':modelo_motherboard', $this->modelo_motherboard);
            $stmt->bindParam(':marca_motherboard', $this->marca_motherboard);
            if (!$stmt->execute()) {
                throw new PDOException("Error executing query (motherboard): " . implode(", ", $stmt->errorInfo()));
            }
            $id_motherboard = $this->db->lastInsertId();

            // Insertar fuente de poder
            $sql = "INSERT INTO fuente_poder (marca_fuente_poder, wattage, id_estado_pieza_fuente) 
                    VALUES (:marca_fuente, :wattage, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca_fuente', $this->marca_fuente);
            $stmt->bindParam(':wattage', $this->wattage_fuente);
            if (!$stmt->execute()) {
                throw new PDOException("Error executing query (fuente_poder): " . implode(", ", $stmt->errorInfo()));
            }
            $id_fuente = $this->db->lastInsertId();

            // Insertar equipo informático
            $sql = "INSERT INTO equipo_informatico (marca_equipo_informatico, id_estado_equipo, id_persona, id_procesador, id_motherboard, id_fuente) 
                    VALUES (:marca, :estado, :persona_id, :id_procesador, :id_motherboard, :id_fuente)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca', $this->marca);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':persona_id', $this->persona_id);
            $stmt->bindParam(':id_procesador', $id_procesador);
            $stmt->bindParam(':id_motherboard', $id_motherboard);
            $stmt->bindParam(':id_fuente', $id_fuente);
            if (!$stmt->execute()) {
                throw new PDOException("Error executing query (equipo_informatico): " . implode(", ", $stmt->errorInfo()));
            }
            $id_equipo_informatico = $this->db->lastInsertId();

            // Insertar RAM
            $sql = "INSERT INTO ram (id_equipo_informatico, marca_ram, tipo_ram, capacidad_ram, frecuencia_ram, id_estado_pieza_ram) 
                    VALUES (:id_equipo_informatico, :marca_ram, :tipo_ram, :capacidad_ram, :frecuencia_ram, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->bindParam(':marca_ram', $this->marca_ram);
            $stmt->bindParam(':tipo_ram', $this->tipo_ram);
            $stmt->bindParam(':capacidad_ram', $this->capacidad_ram);
            $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram);
            if (!$stmt->execute()) {
                throw new PDOException("Error executing query (ram): " . implode(", ", $stmt->errorInfo()));
            }

            $this->db->commit();

            echo "Registro de equipo informático guardado con éxito.";
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }

    public function readOne($id)
    {
        try {
            $sql = "SELECT ei.*, p.marca_procesador, p.nombre_procesador, p.nucleos, p.frecuencia AS frecuencia_procesador, 
                           m.modelo_motherboard, m.marca_motherboard,
                           f.marca_fuente_poder, f.wattage AS wattage_fuente, 
                           r.marca_ram, r.tipo_ram, r.capacidad_ram, r.frecuencia_ram 
                    FROM equipo_informatico ei
                    JOIN procesador p ON ei.id_procesador = p.id_procesador
                    JOIN motherboard m ON ei.id_motherboard = m.id_motherboard
                    JOIN fuente_poder f ON ei.id_fuente = f.id_fuente_poder
                    JOIN ram r ON ei.id_equipo_informatico = r.id_equipo_informatico
                    WHERE ei.id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function readAll()
    {
        try {
            $sql = "SELECT ei.*, 
                           p.marca_procesador, p.nombre_procesador, p.nucleos, p.frecuencia AS frecuencia_procesador, 
                           m.modelo_motherboard, m.marca_motherboard,
                           f.marca_fuente_poder, f.wattage AS wattage, 
                           SUM(r.capacidad_ram) AS capacidad_ram_total,
                           per.nombre, per.apellido,
                           est.estado_equipo_informatico AS estado_equipo_informatico
                    FROM equipo_informatico ei
                    JOIN procesador p ON ei.id_procesador = p.id_procesador
                    JOIN motherboard m ON ei.id_motherboard = m.id_motherboard
                    JOIN fuente_poder f ON ei.id_fuente = f.id_fuente_poder
                    JOIN ram r ON ei.id_equipo_informatico = r.id_equipo_informatico
                    JOIN persona per ON ei.id_persona = per.id_persona
                    JOIN estado_equipo_informatico est ON ei.id_estado_equipo = est.id_estado_equipo_informatico
                    GROUP BY ei.id_equipo_informatico";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function update($id)
    {
        try {
            $this->db->beginTransaction();

            // Actualizar procesador
            $sql = "UPDATE procesador SET marca_procesador = :marca_procesador, nombre_procesador = :nombre_procesador, nucleos = :nucleos, frecuencia = :frecuencia 
                    WHERE id_procesador = (SELECT id_procesador FROM equipo_informatico WHERE id_equipo_informatico = :id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca_procesador', $this->marca_procesador);
            $stmt->bindParam(':nombre_procesador', $this->nombre_procesador);
            $stmt->bindParam(':nucleos', $this->nucleos);
            $stmt->bindParam(':frecuencia', $this->frecuencia_procesador);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar motherboard
            $sql = "UPDATE motherboard SET modelo_motherboard = :modelo_motherboard, marca_motherboard = :marca_motherboard
                    WHERE id_motherboard = (SELECT id_motherboard FROM equipo_informatico WHERE id_equipo_informatico = :id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':modelo_motherboard', $this->modelo_motherboard);
            $stmt->bindParam(':marca_motherboard', $this->marca_motherboard);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar fuente de poder
            $sql = "UPDATE fuente_poder SET marca_fuente_poder = :marca_fuente, wattage = :wattage 
                    WHERE id_fuente_poder = (SELECT id_fuente FROM equipo_informatico WHERE id_equipo_informatico = :id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca_fuente', $this->marca_fuente);
            $stmt->bindParam(':wattage', $this->wattage_fuente);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar equipo informático
            $sql = "UPDATE equipo_informatico SET marca_equipo_informatico = :marca, id_estado_equipo = :estado, id_persona = :persona_id 
                    WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca', $this->marca);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':persona_id', $this->persona_id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar RAM
            $sql = "UPDATE ram SET marca_ram = :marca_ram, tipo_ram = :tipo_ram, capacidad_ram = :capacidad_ram, frecuencia_ram = :frecuencia_ram 
                    WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':marca_ram', $this->marca_ram);
            $stmt->bindParam(':tipo_ram', $this->tipo_ram);
            $stmt->bindParam(':capacidad_ram', $this->capacidad_ram);
            $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            // Eliminar RAM asociada
            $sql = "DELETE FROM ram WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Obtener IDs de componentes asociados
            $sql = "SELECT id_procesador, id_motherboard, id_fuente FROM equipo_informatico WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $componentes = $stmt->fetch(PDO::FETCH_ASSOC);

            // Eliminar equipo informático
            $sql = "DELETE FROM equipo_informatico WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Eliminar procesador asociado
            $sql = "DELETE FROM procesador WHERE id_procesador = :id_procesador";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_procesador', $componentes['id_procesador']);
            $stmt->execute();

            // Eliminar motherboard asociada
            $sql = "DELETE FROM motherboard WHERE id_motherboard = :id_motherboard";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_motherboard', $componentes['id_motherboard']);
            $stmt->execute();

            // Eliminar fuente de poder asociada
            $sql = "DELETE FROM fuente_poder WHERE id_fuente_poder = :id_fuente";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_fuente', $componentes['id_fuente']);
            $stmt->execute();

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>