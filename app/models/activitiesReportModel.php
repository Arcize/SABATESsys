<?php

namespace app\models;

use app\config\DataBase;

class ActivitiesReportModel
{
    private $id_usuario;
    private $actividad;
    private $fecha_actividad;
    private $descripcion;
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function setData($id_usuario, $actividad, $fecha_actividad, $descripcion)
    {
        $this->id_usuario = $id_usuario;
        $this->actividad = $actividad;
        $this->fecha_actividad = $fecha_actividad;
        $this->descripcion = $descripcion;
    }

    // Genera un código único para el reporte de actividades
    private function generateUniqueReportCode($length = 8)
    {
        do {
            $code = strtoupper(bin2hex(random_bytes($length / 2)));
            $exists = $this->checkCodeExistsInDatabase($code);
        } while ($exists);

        return $code;
    }

    // Verifica si el código ya existe en la base de datos
    private function checkCodeExistsInDatabase($code)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reporte_actividades WHERE codigo_reporte_actividades = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Agrega una entrada de evidencia a la tabla evidencia_reporte_actividades.
     *
     * @param int    $id_actividad   ID del reporte de actividad al que se asocia la evidencia.
     * @param string $ruta_evidencia Ruta relativa del archivo de evidencia en el servidor.
     * @param string $tipo_mime      Tipo MIME del archivo (ej. 'image/jpeg').
     * @param int    $tamano_bytes   Tamaño del archivo en bytes.
     * @return bool True en caso de éxito, false en caso de error.
     */
    public function addEvidenceToReport(int $reportId, string $fullPathForDb, string $mimeType, int $fileSize): bool
    {
        // El argumento $fullPathForDb ya debería contener la ruta completa accesible por la web
        // por ejemplo: 'uploads/report_62/682ebe0ad01b7.jpg'

        $query = "INSERT INTO evidencia_reporte_actividades (id_actividad, ruta_evidencia, tipo_mime, tamano_bytes)
              VALUES (:id_actividad, :ruta_completa, :tipo_mime, :tamano_bytes)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_actividad', $reportId, \PDO::PARAM_INT);
        $stmt->bindValue(':ruta_completa', $fullPathForDb, \PDO::PARAM_STR); // ¡Almacena la ruta completa aquí!
        $stmt->bindValue(':tipo_mime', $mimeType, \PDO::PARAM_STR);
        $stmt->bindValue(':tamano_bytes', $fileSize, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function create()
    {
        try {
            // Iniciar la transacción
            $this->db->beginTransaction();

            $codigo = $this->generateUniqueReportCode();
            $sql = "INSERT INTO reporte_actividades (codigo_reporte_actividades, id_usuario, fecha_actividad, titulo_reporte, contenido_reporte) 
                    VALUES (:codigo, :id_usuario, :fecha_actividad, :actividad, :descripcion)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->bindParam(':fecha_actividad', $this->fecha_actividad);
            $stmt->bindParam(':actividad', $this->actividad);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->execute();

            $reportId = $this->db->lastInsertId();

            // Confirmar la transacción si todo fue bien
            $this->db->commit();

            return ($reportId) ? $reportId : false;
        } catch (\PDOException $e) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    /**
     * Obtiene las imágenes asociadas a un reporte de actividades.
     *
     * @param int $reportId El ID del reporte de actividades.
     * @return array|false Un array de arrays con los datos de las imágenes, o false si no se encuentran.
     */
    public function getImagesByReportId(int $reportId)
    {
        // Asegúrate de que el nombre de la tabla y las columnas coincidan con tu DB
        $sql = "SELECT SUBSTRING_INDEX(ruta_evidencia, '/', -1) AS name, ruta_evidencia AS full_path, tipo_mime AS type, tamano_bytes AS size 
                  FROM evidencia_reporte_actividades 
                  WHERE id_actividad = :id_reporte_actividades ";
        $stmt = $this->db->prepare($sql); // Asumo que $this->db es tu objeto PDO o Wrapper de DB
        $stmt->bindValue(':id_reporte_actividades', $reportId, \PDO::PARAM_INT);
        $stmt->execute();

        // Obtener todos los resultados como un array asociativo
        $images = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Devolver las imágenes o un array vacío si no hay
        return $images ?: [];
    }

    /**
     * Elimina una entrada de imagen específica de la base de datos para un reporte dado.
     *
     * @param int $reportId El ID del reporte.
     * @param string $filename El nombre del archivo de la imagen a eliminar.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function deleteImagesForReport(int $reportId): bool
    {
        $query = "DELETE FROM evidencia_reporte_actividades 
                  WHERE id_actividad = :id_reporte_actividades";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_reporte_actividades', $reportId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Obtiene las imágenes asociadas a un reporte de actividades (formato simple).
     *
     * @param int $reportId El ID del reporte de actividades.
     * @return array Un array de arrays con los datos de las imágenes.
     */
    public function getImagesForReport($reportId)
    {
        $sql = "SELECT ruta_evidencia, tipo_mime, tamano_bytes FROM evidencia_reporte_actividades WHERE id_actividad = :report_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':report_id', $reportId, \PDO::PARAM_INT);
        $stmt->execute();
        $images = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $images ?: [];
    }
    /**
     * Elimina una imagen específica asociada a un reporte de actividades.
     *
     * @param int $reportId El ID del reporte de actividades.
     * @param string $fullPath Ruta completa o relativa de la imagen a eliminar.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function deleteImageByNameForReport($reportId, $fullPath)
    {
        $sql = "DELETE FROM evidencia_reporte_actividades WHERE id_actividad = :report_id AND ruta_evidencia = :full_path";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':report_id', $reportId, \PDO::PARAM_INT);
        $stmt->bindValue(':full_path', $fullPath, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Verifica si una imagen existe en un reporte de actividades.
     *
     * @param int $reportId El ID del reporte de actividades.
     * @param string $fullPath Ruta completa o relativa de la imagen.
     * @return bool True si la imagen existe, false en caso contrario.
     */
    public function imageExistsInReport($reportId, $fullPath)
    {
        $sql = "SELECT COUNT(*) as total FROM evidencia_reporte_actividades WHERE id_actividad = :report_id AND ruta_evidencia = :full_path";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':report_id', $reportId, \PDO::PARAM_INT);
        $stmt->bindValue(':full_path', $fullPath, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return isset($result['total']) && $result['total'] > 0;
    }
    public function readOne($id)
    {
        try {
            $sql = "SELECT 
                        r.*, 
                        IFNULL(GROUP_CONCAT(i.ruta_evidencia), '') AS imagenes
                    FROM reporte_actividades r
                    LEFT JOIN evidencia_reporte_actividades i 
                    ON r.id_reporte_actividades = i.id_actividad
                    WHERE r.id_reporte_actividades = :id
                    GROUP BY r.id_reporte_actividades;";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Convertir la cadena de imágenes en un array
            $resultado['imagenes'] = !empty($resultado['imagenes']) ? explode(',', $resultado['imagenes']) : [];

            return $resultado;
        } catch (\PDOException $e) {
            error_log("Error en readOne: " . $e->getMessage());
            return null;
        }
    }



    public function readPage()
    {
        try {
            $sql = "SELECT ra.*, CONCAT(p.nombre, ' ', p.apellido) as nombre_completo FROM reporte_actividades ra
                    JOIN persona p ON ra.id_usuario = p.id_usuario";
            $stmt = $this->db->prepare($sql);
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
            $sql = "SELECT COUNT(*) as total FROM reporte_actividades";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public function update($id, $reportData)
    {
        try {
            $sql = "UPDATE reporte_actividades SET
                    titulo_reporte = :titulo_reporte,
                    fecha_actividad = :fecha_actividad,
                    contenido_reporte = :contenido_reporte
                WHERE id_reporte_actividades = :id";

            $stmt = $this->db->prepare($sql);

            // Aquí es donde vinculas los valores del array $reportData
            // A la consulta SQL usando los nombres correctos de los parámetros
            $stmt->bindParam(':titulo_reporte', $reportData['titulo_reporte']);
            $stmt->bindParam(':fecha_actividad', $reportData['fecha_actividad']);
            $stmt->bindParam(':contenido_reporte', $reportData['contenido_reporte']);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // Asegúrate de vincular el ID como entero

            $stmt->execute();

            // Si la ejecución es exitosa, retorna true.
            // Retornar el ID puede ser confuso si no siempre lo necesitas.
            // Si el objetivo es solo indicar éxito/fracaso, true/false es más claro.
            return true;
        } catch (\PDOException $e) {
            // Mejor usar error_log para errores en producción y evitar exponer detalles sensibles al usuario final
            error_log("Error al actualizar reporte: " . $e->getMessage());
            // Puedes mostrar un mensaje genérico para el usuario, o manejarlo en el controlador
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos al actualizar el reporte.']);
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM reporte_actividades WHERE id_reporte_actividades = :id";
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
