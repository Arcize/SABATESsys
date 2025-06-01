<?php

namespace app\models;

use app\config\DataBase;

class EmployeeModel
{
    private $nombre;
    private $apellido;
    private $cedula;
    private $correo;
    private $departamento;
    private $sexo;
    private $fecha_nac;
    private $db;


    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getData($nombre, $apellido, $cedula, $correo, $departamento, $sexo, $fecha_nac)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
        $this->correo = $correo;
        $this->departamento = $departamento;
        $this->sexo = $sexo;
        $this->fecha_nac = $fecha_nac;
    }

    public function getCedula($cedula)
    {
        // Verifica si la cédula ya existe en la base de datos
        $sql = "SELECT COUNT(*) AS existe_usuario
                FROM persona p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.cedula = :cedula;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;

    }
    public function create()
    {
        try {
            // Consulta SQL con parámetros nombrados
            $sql = "INSERT INTO persona (nombre, apellido, cedula, correo, id_departamento, id_sexo, fecha_nac) 
                    VALUES (:nombre, :apellido, :cedula, :correo, :id_departamento, :id_sexo, :fecha_nac)";
            $stmt = $this->db->prepare($sql);

            // Asignar valores a los parámetros
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':cedula', $this->cedula);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->bindParam(':id_departamento', $this->departamento);
            $stmt->bindParam(':id_sexo', $this->sexo);
            $stmt->bindParam(':fecha_nac', $this->fecha_nac);

            // Ejecutar la consulta
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function readOne($id)
    {
        try {
            $sql = "SELECT p.*, d.nombre_departamento, s.sexo
            FROM persona p
            JOIN departamento d ON p.id_departamento = d.id_departamento
            JOIN sexo s ON p.id_sexo = s.id_sexo WHERE id_persona = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function readPage()
    {
        $sql = "SELECT p.*, d.nombre_departamento, s.sexo
                    FROM persona p
                    JOIN departamento d ON p.id_departamento = d.id_departamento
                    JOIN sexo s ON p.id_sexo = s.id_sexo
                    ORDER BY id_persona";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalRecords()
    {
        $sql = "SELECT COUNT(*) as total FROM persona";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        // Obtener el total de registros
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public function update($id)
    {
        try {
            $sql = "UPDATE persona SET nombre = :nombre, apellido = :apellido, cedula = :cedula, correo = :correo, id_departamento = :id_departamento, id_sexo = :id_sexo, fecha_nac = :fecha_nac WHERE id_persona = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':cedula', $this->cedula);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->bindParam(':id_departamento', $this->departamento);
            $stmt->bindParam(':id_sexo', $this->sexo);
            $stmt->bindParam(':fecha_nac', $this->fecha_nac);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM persona WHERE id_persona = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function verifyCedula($cedula, $id_persona)
    {
        try {
            $sql = "SELECT COUNT(*) FROM persona WHERE cedula = :cedula AND id_persona != :id_persona";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':id_persona', $id_persona);
            $stmt->execute();
            $exist = $stmt->fetchColumn() > 0;
            return $exist;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getCedulaPc($idPC)
    {
        try {
            $sql = "SELECT persona.cedula
                    FROM persona
                    JOIN equipo_informatico ON persona.id_persona = equipo_informatico.id_persona
                    WHERE equipo_informatico.id_equipo_informatico = :idPC;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idPC', $idPC);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        if ($result) {
            return $result['cedula'];
        } else {
            return null; // O manejar el caso en que no se encuentre el ID
        }
    }

    public function deactivate($id)
    {
        $sql = "UPDATE persona SET estado_empleado = 'Inactivo' WHERE id_persona = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function activate($id)
    {
        $sql = "UPDATE persona SET estado_empleado = 'Activo' WHERE id_persona = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function getEstadoEmpleado($id)
    {
        $sql = "SELECT estado_empleado FROM persona WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $row['estado_empleado'] : 'Inactivo';
    }
    public function getRolByPersonaId($id_persona)
    {
        // Busca el rol del usuario asociado a la persona
        $sql = "SELECT r.rol FROM usuario u
                JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.id_usuario = :id_persona LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_persona', $id_persona, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $row['rol'] : null;
    }
    public function getProfile($id_usuario)
    {
        // Obtiene los datos básicos del usuario y persona, incluyendo departamento, sexo y fecha de nacimiento
        $sql = "SELECT p.nombre, p.apellido, p.cedula, p.correo, d.nombre_departamento as departamento, s.sexo, p.fecha_nac
                FROM persona p
                JOIN departamento d ON p.id_departamento = d.id_departamento
                JOIN sexo s ON p.id_sexo = s.id_sexo
                WHERE p.id_usuario = :id_usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateEmail($id_usuario, $correo)
    {
        $sql = "UPDATE persona SET correo = :correo WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':id_usuario', $id_usuario, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEmployeeReportData($id_persona)
    {
        $sql = "SELECT p.*, d.nombre_departamento, s.sexo
                FROM persona p
                JOIN departamento d ON p.id_departamento = d.id_departamento
                JOIN sexo s ON p.id_sexo = s.id_sexo
                WHERE p.id_persona = :id_persona";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_persona', $id_persona, \PDO::PARAM_INT);
        $stmt->execute();
        $employee = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($employee) {
            // Puedes agregar aquí lógica para formatear o agregar campos extra si lo necesitas
            return $employee;
        }
        return null;
    }

    /**
     * Devuelve un array de técnicos (usuarios con rol 3)
     */
    public function getTechnicians()
    {
        $sql = "SELECT u.id_usuario, CONCAT(p.nombre, ' ', p.apellido) as nombre
                FROM usuario u
                JOIN persona p ON u.id_usuario = p.id_usuario
                WHERE u.id_rol = 3";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
