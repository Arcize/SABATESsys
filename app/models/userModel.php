<?php

namespace app\models;

use app\config\DataBase;


class UserModel
{
    private $username;
    private $password;
    private $typeUser = 2;
    private $db;
    private $id_usuario;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }
    public function passwordHash($password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $passwordHash;
    }
    public function setData($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function isAnEmployee($id)
    {
        $sql = "SELECT 1 FROM persona WHERE cedula = :cedula";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cedula', $id, \PDO::PARAM_INT);
        $stmt->execute();
        // Devolver 1 si la identificación existe, de lo contrario 0
        return $stmt->rowCount() > 0 ? 1 : 0;
    }
    public function isAnAdmin($id)
    {
        $sql = "SELECT 1 FROM persona WHERE cedula = :cedula AND id_departamento = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cedula', $id, \PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si la consulta devuelve filas
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            $this->typeUser = 1; // La persona es del departamento de informática
        } else {
            $this->typeUser = 2; // La persona no es del departamento de informática
        }
        // Devolver el tipo de usuario para facilitar las pruebas y validaciones
        return $this->typeUser;
    }
    public function updatePersonIdUser($id)
    {
        $this->id_usuario = $this->db->lastInsertId();
        // Actualizar el registro en la tabla persona para incluir el id_usuario
        $sql_update = "UPDATE persona SET id_usuario = :id_usuario WHERE cedula = :cedula";
        $stmt_update = $this->db->prepare($sql_update);
        $stmt_update->bindParam(':id_usuario', $this->id_usuario, \PDO::PARAM_INT);
        $stmt_update->bindParam(':cedula', $id, \PDO::PARAM_STR);
        $stmt_update->execute();
    }
    public function register()
    {
        try {
            $sql = "INSERT INTO usuario (username, password, id_rol) VALUES ('$this->username', '$this->password', $this->typeUser)";
            $resultQuery = $this->db->query($sql);
            if ($resultQuery) {

                echo "Registro exitoso";
            } else {
                echo "Error en la ejecución de la consulta";
            }
            $resultQuery->closeCursor();
            $_SESSION['register_success'] = "Registro exitoso. Por favor, inicia sesión.";
            header("Location: index.php?view=login");
        } catch (\PDOException $e) {
            echo "Ha ocurrido un error: " . $e->getMessage(); // Mostrar el mensaje de error
        }
    }
    public function readAll()
    {
        try {
            // Consulta SQL con JOIN para obtener los datos de persona, departamento y sexo
            $sql = "SELECT p.cedula, u.username, p.id_usuario, r.rol
                    FROM persona p
                    JOIN usuario u ON p.id_usuario = u.id_usuario
                    JOIN rol r on r.id_rol = u.id_rol";

            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getUserByUsername($username)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                error_log("Usuario no encontrado: " . $username);
            } else {
                error_log("Usuario encontrado: " . print_r($user, true));
            }

            return $user;
        } catch (\PDOException $e) {
            error_log("Ha ocurrido un error al obtener el usuario: " . $e->getMessage());
            echo "Ha ocurrido un error: " . $e->getMessage();
            return false;
        }
    }
    /**
     * Verifica si el usuario tiene un permiso específico.
     *
     * @param int    $userId         El ID del usuario a verificar.
     * @param string $permissionName El nombre del permiso a buscar.
     * @return bool                  True si el usuario tiene el permiso, false de lo contrario.
     */
    public function hasPermission(int $userId, string $permissionName): bool
    {
        $sql="SELECT COUNT(p.id_permisos)
                                   FROM usuario u
                                   JOIN rol r ON u.id_rol = r.id_rol
                                   JOIN roles_permisos rp ON r.id_rol = rp.id_rol
                                   JOIN permisos p ON rp.id_permiso = p.id_permisos
                                   WHERE u.id_usuario = :user_id AND p.nombre_permiso = :permission_name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':permission_name', $permissionName);
        $stmt->execute();

        // Si la consulta devuelve un conteo mayor que 0, significa que el usuario (a través de su rol)
        // tiene el permiso solicitado.
        return $stmt->fetchColumn() > 0;
    }
}
