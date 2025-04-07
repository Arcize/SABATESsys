<?php
namespace app\models;
use app\config\DataBase;


class PcModel
{
    private $fabricante;
    private $estado;
    private $persona_id;
    private $fabricante_procesador;
    private $nombre_procesador;
    private $nucleos;
    private $frecuencia_procesador;
    private $fabricante_motherboard;
    private $modelo_motherboard;
    private $fabricante_fuente;
    private $wattage_fuente;
    private $fabricante_ram;
    private $tipo_ram;
    private $frecuencia_ram;
    private $capacidad_ram;
    private $fabricante_almacenamiento;
    private $tipo_almacenamiento;
    private $capacidad_almacenamiento;
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getData($fabricante, $estado, $persona_id, $fabricante_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $fabricante_motherboard, $modelo_motherboard, $fabricante_fuente, $wattage_fuente, $fabricante_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram, $fabricante_almacenamiento, $tipo_almacenamiento, $capacidad_almacenamiento)
    {
        $this->fabricante = $fabricante;
        $this->estado = $estado;
        $this->persona_id = $persona_id;
        $this->fabricante_procesador = $fabricante_procesador;
        $this->nombre_procesador = $nombre_procesador;
        $this->nucleos = $nucleos;
        $this->frecuencia_procesador = $frecuencia_procesador;
        $this->fabricante_motherboard = $fabricante_motherboard;
        $this->modelo_motherboard = $modelo_motherboard;
        $this->fabricante_fuente = $fabricante_fuente;
        $this->wattage_fuente = $wattage_fuente;
        $this->fabricante_ram = $fabricante_ram;
        $this->tipo_ram = $tipo_ram;
        $this->frecuencia_ram = $frecuencia_ram;
        $this->capacidad_ram = $capacidad_ram;
        $this->fabricante_almacenamiento = $fabricante_almacenamiento;
        $this->tipo_almacenamiento = $tipo_almacenamiento;
        $this->capacidad_almacenamiento = $capacidad_almacenamiento;
    }

    public function create()
    {
        try {
            $this->db->beginTransaction();

            // Insertar equipo informático
            try {
                $sql = "INSERT INTO equipo_informatico (fabricante_equipo_informatico, id_estado_equipo, id_persona) VALUES (:fabricante, :estado, :persona_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':fabricante', $this->fabricante);
                $stmt->bindParam(':estado', $this->estado);
                $stmt->bindParam(':persona_id', $this->persona_id);
                $stmt->execute();
                $id_equipo_informatico = $this->db->lastInsertId();
            } catch (\PDOException $e) {
                error_log("Error al insertar equipo informático: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
                throw new \Exception("Error al insertar equipo informático.");
            }

            // Insertar procesador
            try {
                $sql = "INSERT INTO procesador (id_equipo_informatico_procesador, fabricante_procesador, nombre_procesador, nucleos, frecuencia, id_estado_pieza_procesador) VALUES (:id_equipo_informatico, :fabricante_procesador, :nombre_procesador, :nucleos, :frecuencia, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':fabricante_procesador', $this->fabricante_procesador);
                $stmt->bindParam(':nombre_procesador', $this->nombre_procesador);
                $stmt->bindParam(':nucleos', $this->nucleos);
                $stmt->bindParam(':frecuencia', $this->frecuencia_procesador);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al insertar procesador: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
                throw new \Exception("Error al insertar procesador.");
            }

            // Insertar motherboard
            try {
                $sql = "INSERT INTO motherboard (id_equipo_informatico_motherboard, modelo_motherboard, fabricante_motherboard, id_estado_pieza_motherboard) VALUES (:id_equipo_informatico, :modelo_motherboard, :fabricante_motherboard, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':modelo_motherboard', $this->modelo_motherboard);
                $stmt->bindParam(':fabricante_motherboard', $this->fabricante_motherboard);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al insertar motherboard: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
                throw new \Exception("Error al insertar motherboard.");
            }

            // Insertar fuente de poder
            try {
                $sql = "INSERT INTO fuente_poder (id_equipo_informatico_fuente, fabricante_fuente_poder, wattage, id_estado_pieza_fuente) VALUES (:id_equipo_informatico, :fabricante_fuente, :wattage, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':fabricante_fuente', $this->fabricante_fuente);
                $stmt->bindParam(':wattage', $this->wattage_fuente);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al insertar fuente de poder: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
                throw new \Exception("Error al insertar fuente de poder.");
            }

            // Insertar almacenamiento
            try {
                $sql = "INSERT INTO almacenamiento (id_equipo_informatico_almacenamiento, fabricante_almacenamiento, tipo_almacenamiento, capacidad_almacenamiento, id_estado_pieza_almacenamiento) VALUES (:id_equipo_informatico, :fabricante_almacenamiento, :tipo_almacenamiento, :capacidad_almacenamiento, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':fabricante_almacenamiento', $this->fabricante_almacenamiento);
                $stmt->bindParam(':tipo_almacenamiento', $this->tipo_almacenamiento);
                $stmt->bindParam(':capacidad_almacenamiento', $this->capacidad_almacenamiento);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al insertar almacenamiento: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
                throw new \Exception("Error al insertar almacenamiento.");
            }

            // Insertar RAM
            try {
                $sql = "INSERT INTO ram (id_equipo_informatico_ram, fabricante_ram, tipo_ram, capacidad_ram, frecuencia_ram, id_estado_pieza_ram) VALUES (:id_equipo_informatico, :fabricante_ram, :tipo_ram, :capacidad_ram, :frecuencia_ram, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':fabricante_ram', $this->fabricante_ram);
                $stmt->bindParam(':tipo_ram', $this->tipo_ram);
                $stmt->bindParam(':capacidad_ram', $this->capacidad_ram);
                $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al insertar RAM: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
                throw new \Exception("Error al insertar RAM.");
            }

            $this->db->commit();
            echo "Registro de equipo informático guardado con éxito.";
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Transacción fallida: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            echo "Ocurrió un error. Por favor, revisa el archivo de registro.";
        }
    }

    public function readOne($id)
    {
        try {
            $sql = "SELECT ei.*, p.fabricante_procesador, p.nombre_procesador, p.nucleos, p.frecuencia AS frecuencia_procesador, 
                           m.modelo_motherboard, m.fabricante_motherboard,
                           f.fabricante_fuente_poder, f.wattage AS wattage_fuente, 
                           r.fabricante_ram, r.tipo_ram, r.capacidad_ram, r.frecuencia_ram,
                           a.fabricante_almacenamiento, a.tipo_almacenamiento, a.capacidad_almacenamiento
                    FROM equipo_informatico ei
                    JOIN procesador p ON ei.id_equipo_informatico = p.id_equipo_informatico_procesador
                    JOIN motherboard m ON ei.id_equipo_informatico = m.id_equipo_informatico_motherboard
                    JOIN fuente_poder f ON ei.id_equipo_informatico = f.id_equipo_informatico_fuente
                    JOIN ram r ON ei.id_equipo_informatico = r.id_equipo_informatico_ram
                    JOIN almacenamiento a ON ei.id_equipo_informatico = a.id_equipo_informatico_almacenamiento
                    WHERE ei.id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function readAll()
    {
        try {
            $sql = "SELECT ei.*, 
                           p.fabricante_procesador, p.nombre_procesador, p.nucleos, p.frecuencia AS frecuencia_procesador, 
                           m.modelo_motherboard, m.fabricante_motherboard,
                           f.fabricante_fuente_poder, f.wattage AS wattage, 
                           SUM(r.capacidad_ram) AS capacidad_ram_total,
                           per.nombre, per.apellido,
                           est.estado_equipo_informatico AS estado_equipo_informatico,
                           SUM(a.capacidad_almacenamiento) AS almacenamiento_total
                    FROM equipo_informatico ei
                    JOIN procesador p ON ei.id_equipo_informatico = p.id_equipo_informatico_procesador
                    JOIN motherboard m ON ei.id_equipo_informatico = m.id_equipo_informatico_motherboard
                    JOIN fuente_poder f ON ei.id_equipo_informatico = f.id_equipo_informatico_fuente
                    JOIN ram r ON ei.id_equipo_informatico = r.id_equipo_informatico_ram
                    JOIN almacenamiento a ON ei.id_equipo_informatico = a.id_equipo_informatico_almacenamiento
                    JOIN persona per ON ei.id_persona = per.id_persona
                    JOIN estado_equipo_informatico est ON ei.id_estado_equipo = est.id_estado_equipo_informatico
                    GROUP BY ei.id_equipo_informatico";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function update($id)
    {
        try {
            $this->db->beginTransaction();

            try {
                // Actualizar equipo informático
                $sql = "UPDATE equipo_informatico SET fabricante_equipo_informatico = :fabricante, id_estado_equipo = :estado, id_persona = :persona_id 
            WHERE id_equipo_informatico = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':fabricante', $this->fabricante);
                $stmt->bindParam(':estado', $this->estado);
                $stmt->bindParam(':persona_id', $this->persona_id);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al actualizar equipo informático: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            }
            try {
                // Actualizar procesador
                $sql = "UPDATE procesador SET fabricante_procesador = :fabricante_procesador, nombre_procesador = :nombre_procesador, nucleos = :nucleos, frecuencia = :frecuencia 
                    WHERE id_equipo_informatico_procesador = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':fabricante_procesador', $this->fabricante_procesador);
                $stmt->bindParam(':nombre_procesador', $this->nombre_procesador);
                $stmt->bindParam(':nucleos', $this->nucleos);
                $stmt->bindParam(':frecuencia', $this->frecuencia_procesador);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al actualizar procesador: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            }
            try {
                // Actualizar motherboard
                $sql = "UPDATE motherboard SET modelo_motherboard = :modelo_motherboard, fabricante_motherboard = :fabricante_motherboard
                    WHERE id_equipo_informatico_motherboard = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':modelo_motherboard', $this->modelo_motherboard);
                $stmt->bindParam(':fabricante_motherboard', $this->fabricante_motherboard);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al actualizar motherboard: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            }
            try {
                // Actualizar fuente de poder
                $sql = "UPDATE fuente_poder SET fabricante_fuente_poder = :fabricante_fuente, wattage = :wattage 
                    WHERE id_equipo_informatico_fuente = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':fabricante_fuente', $this->fabricante_fuente);
                $stmt->bindParam(':wattage', $this->wattage_fuente);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Error al actualizar fuente: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            }


            // Actualizar almacenamiento
            $sql = "UPDATE almacenamiento SET fabricante_almacenamiento = :fabricante_almacenamiento, tipo_almacenamiento = :tipo_almacenamiento, capacidad_almacenamiento = :capacidad_almacenamiento 
                    WHERE id_equipo_informatico_almacenamiento = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fabricante_almacenamiento', $this->fabricante_almacenamiento);
            $stmt->bindParam(':tipo_almacenamiento', $this->tipo_almacenamiento);
            $stmt->bindParam(':capacidad_almacenamiento', $this->capacidad_almacenamiento);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar RAM
            $sql = "UPDATE ram SET fabricante_ram = :fabricante_ram, tipo_ram = :tipo_ram, capacidad_ram = :capacidad_ram, frecuencia_ram = :frecuencia_ram 
                    WHERE id_equipo_informatico_ram = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fabricante_ram', $this->fabricante_ram);
            $stmt->bindParam(':tipo_ram', $this->tipo_ram);
            $stmt->bindParam(':capacidad_ram', $this->capacidad_ram);
            $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            error_log("Error en la actualización: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            // Eliminar equipo informático
            $sql = "DELETE FROM equipo_informatico WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->db->commit();
        } catch (\PDOException $e) {
            error_log($e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
    public function getPcId($cedula)
    {
        try {
            $sql = "SELECT equipo_informatico.id_equipo_informatico
                    FROM persona
                    JOIN equipo_informatico ON persona.id_persona = equipo_informatico.id_persona
                    WHERE persona.cedula = :cedula;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch (\PDOException $e) {
            error_log("Error al obtener ID de PC: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            return null; // O manejar el error de otra manera
        }
        if ($result) {
            return $result['id_equipo_informatico'];
        } else {
            return null; // O manejar el caso en que no se encuentre el ID
        }
    }
}
