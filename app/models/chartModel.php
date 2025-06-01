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
                'backgroundColor' => ['#211C84', '#7A73D1'], // Colores personalizados
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
                'backgroundColor' => ['#2E8B57', '#1E90FF', '#FFA500', '#B0B0B0', '#9966FF'], // Colores personalizados
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
                'backgroundColor' => ['#211C84'], // Colores personalizados
            ];
            // Gráfica de reportes de fallas por semana y estado en el mes actual (corregido JOIN)
            $sql4 = "SELECT 
                WEEK(rf.fecha_hora_reporte_fallas, 3) - WEEK(DATE_SUB(rf.fecha_hora_reporte_fallas, INTERVAL DAY(rf.fecha_hora_reporte_fallas)-1 DAY), 3) + 1 AS semana,
                erf.estado_reporte_fallas AS estado,
                COUNT(*) AS total
            FROM reporte_fallas rf
            JOIN estado_reporte_fallas erf ON rf.id_estado_reporte_fallas = erf.id_estado_reporte_fallas
            WHERE MONTH(rf.fecha_hora_reporte_fallas) = MONTH(CURDATE())
              AND YEAR(rf.fecha_hora_reporte_fallas) = YEAR(CURDATE())
            GROUP BY semana, estado
            ORDER BY semana, FIELD(estado, 'Pendiente', 'En Proceso', 'Completado');";
            $stmt4 = $this->db->prepare($sql4);
            $stmt4->execute();
            $data4 = $stmt4->fetchAll(\PDO::FETCH_ASSOC);

            // Procesar los datos para el gráfico de barras apiladas
            // Determinar el número de semanas del mes actual
            $primerDiaMes = date('Y-m-01');
            $ultimoDiaMes = date('Y-m-t');
            $numSemanas = (int)date('W', strtotime($ultimoDiaMes)) - (int)date('W', strtotime($primerDiaMes)) + 1;
            $semanas = [];
            for ($i = 1; $i <= $numSemanas; $i++) {
                $semanas[] = 'Semana ' . $i;
            }
            // Obtener todos los estados posibles desde la tabla estado_reporte_fallas
            $sqlEstados = "SELECT estado_reporte_fallas FROM estado_reporte_fallas ORDER BY FIELD(estado_reporte_fallas, 'Pendiente', 'En Proceso', 'Completado')";
            $stmtEstados = $this->db->prepare($sqlEstados);
            $stmtEstados->execute();
            $estados = array_column($stmtEstados->fetchAll(\PDO::FETCH_ASSOC), 'estado_reporte_fallas');
            // Inicializar dataset dinámicamente
            $dataset = [];
            foreach ($estados as $estado) {
                $dataset[$estado] = array_fill(0, $numSemanas, 0);
            }
            foreach ($data4 as $row) {
                $semanaIdx = ((int)$row['semana']) - 1;
                $estado = $row['estado'];
                if (isset($dataset[$estado]) && isset($semanas[$semanaIdx])) {
                    $dataset[$estado][$semanaIdx] = (int)$row['total'];
                }
            }
            // Preparar datasets para Chart.js
            $chartDatasets = [];
            $colores = ['#ffa500', '#007bff', '#28a745'];
            foreach ($estados as $i => $estado) {
                $chartDatasets[] = [
                    'label' => $estado,
                    'data' => $dataset[$estado],
                    'backgroundColor' => $colores[$i % count($colores)],
                ];
            }
            $charts[] = [
                'id' => 'reportes_fallas_mensual',
                'title' => 'Reportes de Fallas por Semana (Mes Actual)',
                'panel' => 4,
                'type' => 'bar',
                'labels' => $semanas,
                'datasets' => $chartDatasets,
                'stacked' => true,
            ];
            // Agregar más consultas para otras gráficas si es necesario

            return $charts;
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los datos: " . $e->getMessage());
        }
    }
}
