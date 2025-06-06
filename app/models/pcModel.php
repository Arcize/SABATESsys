<?php

namespace app\models;

use app\config\DataBase;

class PcModel
{
    private $fabricante;
    private $estado;
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

    // Refactor: elimina cédula
    public function getData($fabricante, $estado, $fabricante_procesador, $nombre_procesador, $nucleos, $frecuencia_procesador, $fabricante_motherboard, $modelo_motherboard, $fabricante_fuente, $wattage_fuente, $fabricante_ram, $tipo_ram, $frecuencia_ram, $capacidad_ram, $fabricante_almacenamiento, $tipo_almacenamiento, $capacidad_almacenamiento)
    {
        $this->fabricante = $fabricante;
        $this->estado = $estado;
        $this->fabricante_procesador = $fabricante_procesador;
        $this->nombre_procesador = $nombre_procesador;
        $this->nucleos = $nucleos;
        $this->frecuencia_procesador = $frecuencia_procesador;
        $this->fabricante_motherboard = $fabricante_motherboard;
        $this->modelo_motherboard = $modelo_motherboard;
        $this->fabricante_fuente = $fabricante_fuente;
        $this->wattage_fuente = $wattage_fuente;
        $this->fabricante_ram = $fabricante_ram;
        $this->tipo_ram = $tipo_ram; // ahora string único
        $this->frecuencia_ram = $frecuencia_ram;
        $this->capacidad_ram = $capacidad_ram;
        $this->fabricante_almacenamiento = $fabricante_almacenamiento;
        $this->tipo_almacenamiento = $tipo_almacenamiento;
        $this->capacidad_almacenamiento = $capacidad_almacenamiento;
    }

    // Genera un código único hexadecimal de 8 dígitos para el equipo
    public function generateUniquePcCode($length = 8)
    {
        do {
            $code = strtoupper(bin2hex(random_bytes($length / 2)));
            $exists = $this->checkPcCodeExistsInDatabase($code);
        } while ($exists);
        return $code;
    }

    // Verifica si el código de equipo ya existe en la base de datos
    public function checkPcCodeExistsInDatabase($code)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM equipo_informatico WHERE codigo_equipo = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Refactor: create sin asignación de persona
    public function create()
    {
        try {
            $this->db->beginTransaction();

            // Generar código único para el equipo
            $codigo_equipo = $this->generateUniquePcCode();

            // Insertar equipo informático
            $sql = "INSERT INTO equipo_informatico (fabricante_equipo_informatico, id_estado_equipo, codigo_equipo) 
                    VALUES (:fabricante, :estado, :codigo_equipo)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fabricante', $this->fabricante);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':codigo_equipo', $codigo_equipo);
            $stmt->execute();
            $id_equipo_informatico = $this->db->lastInsertId();

            // Insertar procesador
            $sql = "INSERT INTO procesador (id_equipo_informatico_procesador, fabricante_procesador, nombre_procesador, nucleos, frecuencia, id_estado_pieza_procesador) VALUES (:id_equipo_informatico, :fabricante_procesador, :nombre_procesador, :nucleos, :frecuencia, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->bindParam(':fabricante_procesador', $this->fabricante_procesador);
            $stmt->bindParam(':nombre_procesador', $this->nombre_procesador);
            $stmt->bindParam(':nucleos', $this->nucleos);
            $stmt->bindParam(':frecuencia', $this->frecuencia_procesador);
            $stmt->execute();

            // Insertar motherboard
            $sql = "INSERT INTO motherboard (id_equipo_informatico_motherboard, modelo_motherboard, fabricante_motherboard, id_estado_pieza_motherboard) VALUES (:id_equipo_informatico, :modelo_motherboard, :fabricante_motherboard, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->bindParam(':modelo_motherboard', $this->modelo_motherboard);
            $stmt->bindParam(':fabricante_motherboard', $this->fabricante_motherboard);
            $stmt->execute();

            // Insertar fuente de poder
            $sql = "INSERT INTO fuente_poder (id_equipo_informatico_fuente, fabricante_fuente_poder, wattage, id_estado_pieza_fuente) VALUES (:id_equipo_informatico, :fabricante_fuente, :wattage, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->bindParam(':fabricante_fuente', $this->fabricante_fuente);
            $stmt->bindParam(':wattage', $this->wattage_fuente);
            $stmt->execute();

            // Insertar módulos de almacenamiento (arrays)
            if (is_array($this->fabricante_almacenamiento)) {
                for ($i = 0; $i < count($this->fabricante_almacenamiento); $i++) {
                    $sql = "INSERT INTO almacenamiento (id_equipo_informatico_almacenamiento, fabricante_almacenamiento, tipo_almacenamiento, capacidad_almacenamiento, id_estado_pieza_almacenamiento) VALUES (:id_equipo_informatico, :fabricante_almacenamiento, :tipo_almacenamiento, :capacidad_almacenamiento, 1)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                    $stmt->bindParam(':fabricante_almacenamiento', $this->fabricante_almacenamiento[$i]);
                    $stmt->bindParam(':tipo_almacenamiento', $this->tipo_almacenamiento[$i]);
                    $stmt->bindParam(':capacidad_almacenamiento', $this->capacidad_almacenamiento[$i]);
                    $stmt->execute();
                }
            } else {
                // Soporte para un solo módulo (por compatibilidad)
                $sql = "INSERT INTO almacenamiento (id_equipo_informatico_almacenamiento, fabricante_almacenamiento, tipo_almacenamiento, capacidad_almacenamiento, id_estado_pieza_almacenamiento) VALUES (:id_equipo_informatico, :fabricante_almacenamiento, :tipo_almacenamiento, :capacidad_almacenamiento, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':fabricante_almacenamiento', $this->fabricante_almacenamiento);
                $stmt->bindParam(':tipo_almacenamiento', $this->tipo_almacenamiento);
                $stmt->bindParam(':capacidad_almacenamiento', $this->capacidad_almacenamiento);
                $stmt->execute();
            }

            // Insertar módulos de RAM (arrays)
            if (is_array($this->fabricante_ram)) {
                for ($i = 0; $i < count($this->fabricante_ram); $i++) {
                    $sql = "INSERT INTO ram (id_equipo_informatico_ram, fabricante_ram, tipo_ram, capacidad_ram, frecuencia_ram, id_estado_pieza_ram) VALUES (:id_equipo_informatico, :fabricante_ram, :tipo_ram, :capacidad_ram, :frecuencia_ram, 1)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                    $stmt->bindParam(':fabricante_ram', $this->fabricante_ram[$i]);
                    $stmt->bindParam(':tipo_ram', $this->tipo_ram); // usar string único
                    $stmt->bindParam(':capacidad_ram', $this->capacidad_ram[$i]);
                    $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram[$i]);
                    $stmt->execute();
                }
            } else {
                // Soporte para un solo módulo (por compatibilidad)
                $sql = "INSERT INTO ram (id_equipo_informatico_ram, fabricante_ram, tipo_ram, capacidad_ram, frecuencia_ram, id_estado_pieza_ram) VALUES (:id_equipo_informatico, :fabricante_ram, :tipo_ram, :capacidad_ram, :frecuencia_ram, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
                $stmt->bindParam(':fabricante_ram', $this->fabricante_ram);
                $stmt->bindParam(':tipo_ram', $this->tipo_ram); // usar string único
                $stmt->bindParam(':capacidad_ram', $this->capacidad_ram);
                $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram);
                $stmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error al crear PC: " . $e->getMessage());
            return false;
        }
    }

    public function readOne($id)
    {
        try {
            // Datos generales (puedes dejar el JOIN solo para una RAM y un almacenamiento)
            $sql = "SELECT ei.*, p.fabricante_procesador, p.nombre_procesador, p.nucleos, p.frecuencia AS frecuencia_procesador, 
                       m.modelo_motherboard, m.fabricante_motherboard,
                       f.fabricante_fuente_poder, f.wattage AS wattage_fuente, 
                       pe.cedula
                FROM equipo_informatico ei
                JOIN procesador p ON ei.id_equipo_informatico = p.id_equipo_informatico_procesador
                JOIN motherboard m ON ei.id_equipo_informatico = m.id_equipo_informatico_motherboard
                JOIN fuente_poder f ON ei.id_equipo_informatico = f.id_equipo_informatico_fuente
                LEFT JOIN persona pe ON ei.id_persona = pe.id_persona
                WHERE ei.id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Traer todos los módulos de RAM
            $sqlRam = "SELECT fabricante_ram, tipo_ram, capacidad_ram, frecuencia_ram 
                       FROM ram WHERE id_equipo_informatico_ram = :id";
            $stmtRam = $this->db->prepare($sqlRam);
            $stmtRam->bindParam(':id', $id);
            $stmtRam->execute();
            $ramData = $stmtRam->fetchAll(\PDO::FETCH_ASSOC);

            // --- NUEVO: obtener tipo_ram único (el primero, si hay módulos) ---
            $tipo_ram_unico = '';
            $capacidad_ram_total = 0;
            if (count($ramData) > 0) {
                $tipo_ram_unico = $ramData[0]['tipo_ram'];
                foreach ($ramData as $ram) {
                    $capacidad_ram_total += (int)$ram['capacidad_ram'];
                }
            }
            $data['tipo_ram'] = $tipo_ram_unico;
            $data['capacidad_ram_total'] = $capacidad_ram_total;

            // Traer todos los módulos de almacenamiento
            $sqlStorage = "SELECT fabricante_almacenamiento, tipo_almacenamiento, capacidad_almacenamiento 
                           FROM almacenamiento WHERE id_equipo_informatico_almacenamiento = :id";
            $stmtStorage = $this->db->prepare($sqlStorage);
            $stmtStorage->bindParam(':id', $id);
            $stmtStorage->execute();
            $storageData = $stmtStorage->fetchAll(\PDO::FETCH_ASSOC);

            // Añadir los arrays al resultado principal
            $data['ramData'] = $ramData;
            $data['storageData'] = $storageData;

            // --- NUEVO: obtener estado textual ---
            $sqlEstado = "SELECT estado_equipo_informatico FROM estado_equipo_informatico WHERE id_estado_equipo_informatico = :id_estado";
            $stmtEstado = $this->db->prepare($sqlEstado);
            $stmtEstado->bindParam(':id_estado', $data['id_estado_equipo']);
            $stmtEstado->execute();
            $data['estado_equipo_informatico'] = $stmtEstado->fetchColumn();

            // --- NUEVO: obtener nombre y cédula de la persona asignada ---
            if (!empty($data['id_persona'])) {
                $sqlPersona = "SELECT nombre, apellido, cedula FROM persona WHERE id_persona = :id_persona";
                $stmtPersona = $this->db->prepare($sqlPersona);
                $stmtPersona->bindParam(':id_persona', $data['id_persona']);
                $stmtPersona->execute();
                $persona = $stmtPersona->fetch(\PDO::FETCH_ASSOC);
                if ($persona) {
                    $data['nombre_completo'] = $persona['nombre'] . ' ' . $persona['apellido'];
                    $data['cedula_persona'] = $persona['cedula'];
                } else {
                    $data['nombre_completo'] = '';
                    $data['cedula_persona'] = '';
                }
            } else {
                $data['nombre_completo'] = '';
                $data['cedula_persona'] = '';
            }

            return $data;
        } catch (\PDOException $e) {
            error_log("Error al leer PC: " . $e->getMessage());
            return null;
        }
    }

    public function readPage()
    {
        try {
            $sql = "SELECT 
                        ei.*, 
                        p.fabricante_procesador, p.nombre_procesador, p.nucleos, p.frecuencia AS frecuencia_procesador, 
                        CONCAT(m.fabricante_motherboard, ' ' ,m.modelo_motherboard) AS motherboard,
                        CONCAT(f.fabricante_fuente_poder, ' ' ,f.wattage, 'W') AS fuente, 
                        CONCAT(per.nombre, ' ', per.apellido) AS nombre_completo,
                        est.estado_equipo_informatico AS estado_equipo_informatico,
                        CONCAT(ram_sum.capacidad_ram_total, 'Gb') AS capacidad_ram_total,
                        CONCAT(storage_sum.almacenamiento_total, 'Gb') AS almacenamiento_total
                    FROM equipo_informatico ei
                    JOIN procesador p ON ei.id_equipo_informatico = p.id_equipo_informatico_procesador
                    JOIN motherboard m ON ei.id_equipo_informatico = m.id_equipo_informatico_motherboard
                    JOIN fuente_poder f ON ei.id_equipo_informatico = f.id_equipo_informatico_fuente
                    LEFT JOIN persona per ON ei.id_persona = per.id_persona
                    JOIN estado_equipo_informatico est ON ei.id_estado_equipo = est.id_estado_equipo_informatico
                    LEFT JOIN (
                        SELECT id_equipo_informatico_ram, SUM(capacidad_ram) AS capacidad_ram_total
                        FROM ram
                        GROUP BY id_equipo_informatico_ram
                    ) ram_sum ON ei.id_equipo_informatico = ram_sum.id_equipo_informatico_ram
                    LEFT JOIN (
                        SELECT id_equipo_informatico_almacenamiento, SUM(capacidad_almacenamiento) AS almacenamiento_total
                        FROM almacenamiento
                        GROUP BY id_equipo_informatico_almacenamiento
                    ) storage_sum ON ei.id_equipo_informatico = storage_sum.id_equipo_informatico_almacenamiento
                    ORDER BY ei.id_equipo_informatico";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error al leer todas las PCs: " . $e->getMessage());
            return [];
        }
    }
    public function getTotalRecords()
    {
        $sql = "SELECT COUNT(*) as total FROM equipo_informatico";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        // Obtener el total de registros
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }
    // Refactor: update sin asignación de persona
    public function update($id)
    {
        try {
            $this->db->beginTransaction();

            // Actualizar equipo informático
            $sql = "UPDATE equipo_informatico SET fabricante_equipo_informatico = :fabricante, id_estado_equipo = :estado 
                    WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fabricante', $this->fabricante);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

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

            // Actualizar motherboard
            $sql = "UPDATE motherboard SET modelo_motherboard = :modelo_motherboard, fabricante_motherboard = :fabricante_motherboard
                    WHERE id_equipo_informatico_motherboard = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':modelo_motherboard', $this->modelo_motherboard);
            $stmt->bindParam(':fabricante_motherboard', $this->fabricante_motherboard);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar fuente de poder
            $sql = "UPDATE fuente_poder SET fabricante_fuente_poder = :fabricante_fuente, wattage = :wattage 
                    WHERE id_equipo_informatico_fuente = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fabricante_fuente', $this->fabricante_fuente);
            $stmt->bindParam(':wattage', $this->wattage_fuente);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // --- RAM ---
            // Eliminar todos los módulos de RAM existentes
            $sql = "DELETE FROM ram WHERE id_equipo_informatico_ram = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Insertar los módulos de RAM actuales
            if (is_array($this->fabricante_ram)) {
                for ($i = 0; $i < count($this->fabricante_ram); $i++) {
                    $sql = "INSERT INTO ram (id_equipo_informatico_ram, fabricante_ram, tipo_ram, capacidad_ram, frecuencia_ram, id_estado_pieza_ram) VALUES (:id, :fabricante_ram, :tipo_ram, :capacidad_ram, :frecuencia_ram, 1)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':fabricante_ram', $this->fabricante_ram[$i]);
                    $stmt->bindParam(':tipo_ram', $this->tipo_ram); // usar string único
                    $stmt->bindParam(':capacidad_ram', $this->capacidad_ram[$i]);
                    $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram[$i]);
                    $stmt->execute();
                }
            } else {
                // Soporte para un solo módulo (por compatibilidad)
                $sql = "INSERT INTO ram (id_equipo_informatico_ram, fabricante_ram, tipo_ram, capacidad_ram, frecuencia_ram, id_estado_pieza_ram) VALUES (:id, :fabricante_ram, :tipo_ram, :capacidad_ram, :frecuencia_ram, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':fabricante_ram', $this->fabricante_ram);
                $stmt->bindParam(':tipo_ram', $this->tipo_ram); // usar string único
                $stmt->bindParam(':capacidad_ram', $this->capacidad_ram);
                $stmt->bindParam(':frecuencia_ram', $this->frecuencia_ram);
                $stmt->execute();
            }

            // --- Almacenamiento ---
            // Eliminar todos los módulos de almacenamiento existentes
            $sql = "DELETE FROM almacenamiento WHERE id_equipo_informatico_almacenamiento = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Insertar los módulos de almacenamiento actuales
            if (is_array($this->fabricante_almacenamiento)) {
                for ($i = 0; $i < count($this->fabricante_almacenamiento); $i++) {
                    $sql = "INSERT INTO almacenamiento (id_equipo_informatico_almacenamiento, fabricante_almacenamiento, tipo_almacenamiento, capacidad_almacenamiento, id_estado_pieza_almacenamiento) VALUES (:id, :fabricante_almacenamiento, :tipo_almacenamiento, :capacidad_almacenamiento, 1)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':fabricante_almacenamiento', $this->fabricante_almacenamiento[$i]);
                    $stmt->bindParam(':tipo_almacenamiento', $this->tipo_almacenamiento[$i]);
                    $stmt->bindParam(':capacidad_almacenamiento', $this->capacidad_almacenamiento[$i]);
                    $stmt->execute();
                }
            } else {
                // Soporte para un solo módulo (por compatibilidad)
                $sql = "INSERT INTO almacenamiento (id_equipo_informatico_almacenamiento, fabricante_almacenamiento, tipo_almacenamiento, capacidad_almacenamiento, id_estado_pieza_almacenamiento) VALUES (:id, :fabricante_almacenamiento, :tipo_almacenamiento, :capacidad_almacenamiento, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':fabricante_almacenamiento', $this->fabricante_almacenamiento);
                $stmt->bindParam(':tipo_almacenamiento', $this->tipo_almacenamiento);
                $stmt->bindParam(':capacidad_almacenamiento', $this->capacidad_almacenamiento);
                $stmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error al actualizar PC: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM equipo_informatico WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            error_log("Error al eliminar PC: " . $e->getMessage());
            return false;
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
        } catch (\PDOException $e) {
            error_log("Error al obtener ID de PC: " . $e->getMessage(), 3, "C:/xampp/htdocs/SABATES/error_log.txt");
            return null; // O manejar el error de otra manera
        }
        if ($result) {
            return $result['id_equipo_informatico'];
        } else {
            return null; // O manejar el caso en que no se encuentre el ID
        }
    }

    /**
     * Asigna o reasigna un equipo a una persona por cédula
     * @param int $id_equipo_informatico
     * @param string $cedula
     * @return bool
     */
    public function assignToPerson($id_equipo_informatico, $cedula)
    {
        try {
            // Buscar id_persona por cédula
            $sql = "SELECT id_persona FROM persona WHERE cedula = :cedula";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $id_persona = $stmt->fetchColumn();
            if (!$id_persona) {
                return false; // No existe la persona
            }
            // Verificar si la persona ya tiene un equipo asignado (que no sea este mismo)
            $sql = "SELECT COUNT(*) FROM equipo_informatico WHERE id_persona = :id_persona AND id_equipo_informatico != :id_equipo_informatico";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_persona', $id_persona);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return 'already_assigned';
            }
            // Actualizar el equipo con el nuevo id_persona
            $sql = "UPDATE equipo_informatico SET id_persona = :id_persona WHERE id_equipo_informatico = :id_equipo_informatico";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_persona', $id_persona);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            error_log("Error al asignar/reasignar equipo: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Desasigna un equipo de una persona (pone id_persona en NULL)
     * @param int $id_equipo_informatico
     * @return bool
     */
    public function unassignFromPerson($id_equipo_informatico)
    {
        try {
            $sql = "UPDATE equipo_informatico SET id_persona = NULL WHERE id_equipo_informatico = :id_equipo_informatico";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_equipo_informatico', $id_equipo_informatico);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            error_log("Error al desasignar equipo: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Verifica si una persona ya tiene un equipo asignado
     * @param string $cedula
     * @return bool
     */
    public function personHasAssignedPC($cedula)
    {
        $sql = "SELECT COUNT(*) FROM equipo_informatico ei
                JOIN persona p ON ei.id_persona = p.id_persona
                WHERE p.cedula = :cedula";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function updateState($id, $estado)
    {
        try {
            $sql = "UPDATE equipo_informatico SET id_estado_equipo = :estado WHERE id_equipo_informatico = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':estado', $estado, \PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Error al actualizar estado de equipo: " . $e->getMessage());
            return false;
        }
    }

    public function readAllExceptDeincorporated()
    {
        try {
            $sql = "SELECT 
                        ei.*, 
                        ep.estado_equipo_informatico, 
                        CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo, 
                        pr.nombre_procesador, 
                        CONCAT(m.fabricante_motherboard, ' ', m.modelo_motherboard) AS motherboard,
                        CONCAT(f.fabricante_fuente_poder, ' ', f.wattage, 'W') AS fuente, 
                        CONCAT((SELECT SUM(ram.capacidad_ram) FROM ram WHERE ram.id_equipo_informatico_ram = ei.id_equipo_informatico), 'Gb') AS capacidad_ram_total,
                        CONCAT((SELECT SUM(almacenamiento.capacidad_almacenamiento) FROM almacenamiento WHERE almacenamiento.id_equipo_informatico_almacenamiento = ei.id_equipo_informatico), 'Gb') AS almacenamiento_total
                    FROM equipo_informatico ei
                    LEFT JOIN estado_equipo_informatico ep ON ei.id_estado_equipo = ep.id_estado_equipo_informatico
                    LEFT JOIN persona p ON ei.id_persona = p.id_persona
                    LEFT JOIN procesador pr ON ei.id_equipo_informatico = pr.id_equipo_informatico_procesador
                    LEFT JOIN motherboard m ON ei.id_equipo_informatico = m.id_equipo_informatico_motherboard
                    LEFT JOIN fuente_poder f ON ei.id_equipo_informatico = f.id_equipo_informatico_fuente
                    WHERE ei.id_estado_equipo != 4
                    ORDER BY ei.id_equipo_informatico DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error al leer PCs (excepto desincorporados): " . $e->getMessage());
            return [];
        }
    }

    public function readAllDeincorporated()
    {
        try {
            $sql = "SELECT 
                        ei.*, 
                        ep.estado_equipo_informatico, 
                        CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo, 
                        pr.nombre_procesador, 
                        CONCAT(m.fabricante_motherboard, ' ', m.modelo_motherboard) AS motherboard,
                        CONCAT(f.fabricante_fuente_poder, ' ', f.wattage, 'W') AS fuente, 
                        CONCAT((SELECT SUM(ram.capacidad_ram) FROM ram WHERE ram.id_equipo_informatico_ram = ei.id_equipo_informatico), 'Gb') AS capacidad_ram_total,
                        CONCAT((SELECT SUM(almacenamiento.capacidad_almacenamiento) FROM almacenamiento WHERE almacenamiento.id_equipo_informatico_almacenamiento = ei.id_equipo_informatico), 'Gb') AS almacenamiento_total
                    FROM equipo_informatico ei
                    LEFT JOIN estado_equipo_informatico ep ON ei.id_estado_equipo = ep.id_estado_equipo_informatico
                    LEFT JOIN persona p ON ei.id_persona = p.id_persona
                    LEFT JOIN procesador pr ON ei.id_equipo_informatico = pr.id_equipo_informatico_procesador
                    LEFT JOIN motherboard m ON ei.id_equipo_informatico = m.id_equipo_informatico_motherboard
                    LEFT JOIN fuente_poder f ON ei.id_equipo_informatico = f.id_equipo_informatico_fuente
                    WHERE ei.id_estado_equipo = 4
                    ORDER BY ei.id_equipo_informatico DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error al leer PCs desincorporados: " . $e->getMessage());
            return [];
        }
    }

    public function updateEstado($id_equipo, $id_estado)
    {
        $sql = "UPDATE equipos_informaticos SET id_estado = :id_estado WHERE id_equipo_informatico = :id_equipo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_estado', $id_estado, \PDO::PARAM_INT);
        $stmt->bindParam(':id_equipo', $id_equipo, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
