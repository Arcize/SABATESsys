<?php

namespace app\models;

use app\config\DataBase;


class UserModel
{
    private $id_usuario;
    private $password;
    private $typeUser = 2;
    private $db;


    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }
    public function passwordHash($password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $passwordHash;
    }

    public function saveDashboardConfig($userId, $config)
    {
        try {
            $sql = "INSERT INTO dashboard_config (id_usuario_dashboard, dashboard_config) 
                    VALUES (:id_usuario, :config)
                    ON DUPLICATE KEY UPDATE dashboard_config = :config";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $userId, \PDO::PARAM_INT);
            $stmt->bindParam(':config', $config, \PDO::PARAM_STR);
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function updatePassword($cedula, $hashedPassword)
    {
        try {
            $sql = "SELECT u.id_usuario 
                        FROM persona p
                        JOIN usuario u ON p.id_usuario = u.id_usuario
                        WHERE p.cedula = :cedula";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                return false; // Cedula no encontrada
            }

            $id_usuario = $user['id_usuario'];


            $sql = "UPDATE usuario SET password = :password WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword, \PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, \PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function setData($password)
    {
        $this->password = $password;
    }
    public function updateRole($id, $id_role)
    {
        try {
            $sql = "UPDATE usuario SET id_rol = :rol WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':rol', $id_role, \PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function readOne($id)
    {
        try {
            $sql = "SELECT u.id_usuario, u.username, p.cedula, u.id_rol
            FROM usuario u
            JOIN persona p on u.id_usuario = p.id_usuario
            WHERE u.id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
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
    public function readPage()
    {
        $sql = "SELECT u.id_usuario, p.cedula, r.rol, r.id_rol, concat(p.nombre, ' ', p.apellido) as nombre_completo, d.nombre_departamento
                    FROM usuario u
                    JOIN persona p ON u.id_usuario = p.id_usuario
                    JOIN rol r ON u.id_rol = r.id_rol
                    JOIN departamento d ON p.id_departamento = d.id_departamento
                    ORDER BY u.id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function isSecurityQuestionsSetup($id)
    {
        $sql = "SELECT COUNT(*) AS total FROM usuario_pregunta WHERE id_usuario = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'] > 0;
    }

    public function getAllRoles()
    {
        $sql = "SELECT * FROM rol";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getTotalRecords()
    {
        $sql = "SELECT COUNT(*) as total FROM usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        // Obtener el total de registros
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }
    public function updatePersonIdUser($idLast, $id)
    {
        // Actualizar el registro en la tabla persona para incluir el id_usuario
        $sql_update = "UPDATE persona SET id_usuario = :id_usuario WHERE cedula = :cedula";
        $stmt_update = $this->db->prepare($sql_update);
        $stmt_update->bindParam(':id_usuario', $idLast, \PDO::PARAM_INT);
        $stmt_update->bindParam(':cedula', $id, \PDO::PARAM_STR);
        $stmt_update->execute();
    }
    public function register()
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO usuario (password, id_rol) VALUES (:password, :id_rol)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':password', $this->password, \PDO::PARAM_STR);
            $stmt->bindParam(':id_rol', $this->typeUser, \PDO::PARAM_INT);

            $result = $stmt->execute();
            $id_usuario = $this->db->lastInsertId();

            $this->db->commit();

            if ($result) {
                $_SESSION['register_success'] = "Registro exitoso. Por favor, inicia sesión.";
                header("Location: index.php?view=login");
                return $id_usuario;
            } else {
                echo "Error en la ejecución de la consulta";
                return false;
            }
        } catch (\PDOException $e) {
            $this->db->rollBack();
            echo "Ha ocurrido un error: " . $e->getMessage();
            return false;
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

    public function getUserByCedula($cedula)
    {
        try {
            $sql = "SELECT u.*, p.estado_empleado FROM usuario u
				JOIN persona p on p.id_usuario = u.id_usuario
				WHERE p.cedula = :cedula";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cedula', $cedula, \PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                error_log("Usuario no encontrado: " . $cedula);
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
        $sql = "SELECT COUNT(p.id_permisos)
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

    public function getDashboardConfig($userId)
    {
        try {
            $sql = "SELECT dashboard_config FROM dashboard_config WHERE id_usuario_dashboard = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $userId, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result['dashboard_config'] : false;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Obtener usuario por id_usuario (para verificar contraseña actual)
    public function getUserByIdUsuario($id_usuario)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_usuario, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Actualizar contraseña por id_usuario
    public function updatePasswordByIdUsuario($id_usuario, $hashedPassword)
    {
        try {
            $sql = "UPDATE usuario SET password = :password WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword, \PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
