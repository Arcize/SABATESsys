<?php
include_once("app\models\DB.php");
class employeeModel
{
    private $nombre;
    private $apellido;
    private $cedula;
    private $correo;
    private $departamento;
    private $sexo;
    private $fecha_nac;
    private $usuario;
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

    public function create() {
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
    
            echo "Registro guardado con éxito.";
        } catch (PDOException $e) {
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
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public function readAll() {
        try {
            // Consulta SQL con JOIN para obtener los datos de persona, departamento y sexo
            $sql = "SELECT p.*, d.nombre_departamento, s.sexo
                    FROM persona p
                    JOIN departamento d ON p.id_departamento = d.id_departamento
                    JOIN sexo s ON p.id_sexo = s.id_sexo";
                    
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
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
