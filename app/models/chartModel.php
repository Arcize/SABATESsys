<?php

namespace app\models;

use app\config\DataBase;

class ChartModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function getChartsData()
    {
        try {
            $charts = [];

            // Gráfica de empleados registrados
            $sql1 = "SELECT CASE WHEN id_usuario IS NULL THEN 'No Registrados' 
                     ELSE 'Registrados' 
                     END AS estado, 
                     COUNT(*) AS total 
                     FROM persona 
                     GROUP BY estado;";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute();
            $data1 = $stmt1->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'empleados',
                'title' => 'Empleados',
                'panel' => 1, // Panel donde se mostrará esta gráfica
                'type' => 'doughnut', // Tipo de gráfica
                'labels' => array_column($data1, 'estado'),
                'data' => array_column($data1, 'total'),
                'showCenterText' => true, // Mostrar texto en el centro
            ];
            $sql2 = "SELECT rol.rol AS rol,
                     COUNT(*) AS total
                     FROM usuario
                     JOIN rol ON usuario.id_rol = rol.id_rol
                     GROUP BY rol.rol;";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute();
            $data2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'roles',
                'title' => 'Roles',
                'panel' => 2, // Panel donde se mostrará esta gráfica
                'type' => 'doughnut', // Tipo de gráfica
                'labels' => array_column($data2, 'rol'),
                'data' => array_column($data2, 'total'),
            ];
            $sql3 = "SELECT dias.dia_semana AS dia_semana,
       COALESCE(COUNT(rf.id_reporte_fallas), 0) AS total_reportes
FROM (
    SELECT 'Domingo' AS dia_semana UNION ALL
    SELECT 'Lunes' UNION ALL
    SELECT 'Martes' UNION ALL
    SELECT 'Miércoles' UNION ALL
    SELECT 'Jueves' UNION ALL
    SELECT 'Viernes' UNION ALL
    SELECT 'Sábado'
) dias
LEFT JOIN reporte_fallas rf
ON CASE
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Sunday' THEN 'Domingo'
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Monday' THEN 'Lunes'
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Tuesday' THEN 'Martes'
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Wednesday' THEN 'Miércoles'
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Thursday' THEN 'Jueves'
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Friday' THEN 'Viernes'
    WHEN DAYNAME(rf.fecha_hora_reporte_fallas) = 'Saturday' THEN 'Sábado'
END = dias.dia_semana
AND rf.fecha_hora_reporte_fallas >= CURDATE() - INTERVAL 7 DAY
GROUP BY dias.dia_semana
ORDER BY FIELD(dias.dia_semana, 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');";

            $stmt3 = $this->db->prepare($sql3);
            $stmt3->execute();
            $data3 = $stmt3->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'reportes_fallas',
                'title' => 'Reportes de Fallas',
                'panel' => 3, // Panel donde se mostrará esta gráfica
                'type' => 'bar', // Tipo de gráfica
                'labels' => array_column($data3, 'dia_semana'), // Etiquetas en español
                'data' => array_column($data3, 'total_reportes'), // Datos de los reportes
            ];
            // Agregar más consultas para otras gráficas si es necesario

            return $charts;
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los datos: " . $e->getMessage());
        }
    }
}
