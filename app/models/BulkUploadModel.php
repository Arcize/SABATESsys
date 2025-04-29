<?php

namespace app\models;

use app\config\DataBase;

use PhpOffice\PhpSpreadsheet\IOFactory;

class BulkUploadModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function processExcel($tmpPath)
    {
        $this->db->beginTransaction(); // Iniciar la transacción

        try {
            $spreadsheet = IOFactory::load($tmpPath);
            $activeSheet = $spreadsheet->getActiveSheet();

            $rowNumber = 0;
            foreach ($activeSheet->getRowIterator() as $row) {
                $rowNumber++;
                if ($rowNumber === 1) {
                    continue; // Omitir la fila de encabezados si existe
                }

                $cellIterator = $row->getCellIterator();
                $rowData = [];
                foreach ($cellIterator as $celda) {
                    $rowData[] = $celda->getValue();
                }

                $this->executeQuery($rowData);

                // Si en executeQuery() ocurre una excepción y no se captura allí,
                // la transacción se hará rollback automáticamente al salir del try...catch
            }

            $this->db->commit(); // Si todo fue exitoso, confirmar la transacción
            return true; // Indica que el procesamiento fue exitoso

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            $this->db->rollBack(); // En caso de error de lectura, deshacer la transacción
            error_log("Error al leer el archivo Excel: " . $e->getMessage());
            return false;
        } catch (\PDOException $e) {
            $this->db->rollBack(); // En caso de error de base de datos, deshacer la transacción
            error_log("Error de base de datos durante el procesamiento del Excel: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->db->rollBack(); // En caso de otros errores, deshacer la transacción
            error_log("Error general durante el procesamiento del Excel: " . $e->getMessage());
            return false;
        } finally {
            // Opcional: Eliminar el archivo temporal después de procesarlo
            if (file_exists($tmpPath)) {
                unlink($tmpPath);
            }
        }
    }

    public function executeQuery($rowData)
    {
        // Asumiendo el siguiente orden de columnas en el Excel:
        // 0: Nombre, 1: Apellido, 2: Cédula, 3: Correo, 4: ID Departamento, 5: ID Sexo, 6: Fecha de Nacimiento

        if (isset($rowData[0]) && isset($rowData[1]) && isset($rowData[2]) &&
            isset($rowData[3]) && isset($rowData[4]) && isset($rowData[5]) &&
            isset($rowData[6])) {

            $sql = "INSERT INTO persona (nombre, apellido, cedula, correo, id_departamento, id_sexo, fecha_nac)
                    VALUES (:nombre, :apellido, :cedula, :correo, :id_departamento, :id_sexo, :fecha_nac)";

            $stmt = $this->db->prepare($sql);

            $nombre = trim($rowData[0]);
            $apellido = trim($rowData[1]);
            $cedula = trim($rowData[2]);
            $correo = trim($rowData[3]);
            $id_departamento = trim($rowData[4]);
            $id_sexo = trim($rowData[5]);
            $fecha_nac = trim($rowData[6]);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':id_departamento', $id_departamento);
            $stmt->bindParam(':id_sexo', $id_sexo);
            $stmt->bindParam(':fecha_nac', $fecha_nac);

            try {
                $stmt->execute();
                // Éxito al insertar esta fila
            } catch (\PDOException $e) {
                error_log("Error al insertar persona: " . $e->getMessage() . " - Datos: " . json_encode($rowData));
                // Lanzar la excepción para que la transacción en processExcel() haga rollback
                throw $e;
            }

        } else {
            error_log("Error: No se encontraron suficientes columnas en la fila del Excel para insertar una persona: " . json_encode($rowData));
            // Considera si lanzar una excepción aquí también, dependiendo de la severidad del error
            return false; // O lanza una excepción
        }
    }
}