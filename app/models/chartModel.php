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
            $sql1 = "SELECT CASE WHEN id_usuario IS NULL THEN 'Sin Registrar' 
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
                'title' => 'Empleados Registrados',
                'panel' => 1, // Panel donde se mostrará esta gráfica
                'type' => 'doughnut', // Tipo de gráfica
                'labels' => array_column($data1, 'estado'),
                'data' => array_column($data1, 'total'),
                'showCenterText' => true, // Mostrar texto en el centro
                'backgroundColor' => ['#211C84', '#7A73D1'], // Colores personalizados
            ];
            // Gráfica de roles (restaurada)
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
            // Gráfica de distribución de reportes de actividades por tipo de actividad (ahora en el panel 3)
            $sql3 = "SELECT ta.tipo_actividad AS tipo_actividad, COUNT(ra.id_reporte_actividades) AS total
                     FROM tipo_actividad ta
                     LEFT JOIN reporte_actividades ra ON ra.id_tipo_reporte = ta.id_tipo_actividad
                        AND MONTH(ra.fecha_actividad) = MONTH(CURDATE())
                        AND YEAR(ra.fecha_actividad) = YEAR(CURDATE())
                     GROUP BY ta.tipo_actividad
                     ORDER BY total DESC, ta.tipo_actividad ASC;";
            $stmt3 = $this->db->prepare($sql3);
            $stmt3->execute();
            $data3 = $stmt3->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'actividades_por_tipo',
                'title' => 'Reportes de Actividades por Tipo',
                'panel' => 3, // Panel donde se mostrará esta gráfica
                'type' => 'bar', // Tipo de gráfica
                'labels' => array_column($data3, 'tipo_actividad'),
                'data' => array_column($data3, 'total'),
                'backgroundColor' => ['#2E8B57', '#1E90FF', '#FFA500', '#B0B0B0', '#9966FF'], // Colores personalizados
            ];
            // Gráfica de reportes de fallas por semana y estado en el mes actual (corregido JOIN)
            $sql4 = "SELECT 
                FLOOR((DAY(rf.fecha_hora_reporte_fallas) - 1) / 7) + 1 AS semana,
                erf.estado_reporte_fallas AS estado,
                COUNT(*) AS total
            FROM reporte_fallas rf
            JOIN estado_reporte_fallas erf ON rf.id_estado_reporte_fallas = erf.id_estado_reporte_fallas
            WHERE MONTH(rf.fecha_hora_reporte_fallas) = MONTH(CURDATE())
              AND YEAR(rf.fecha_hora_reporte_fallas) = YEAR(CURDATE())
            GROUP BY semana, estado
            ORDER BY semana, FIELD(estado, 'Pendiente', 'En Proceso', 'Completado', 'Duplicado', 'Inconsistente');";
            $stmt4 = $this->db->prepare($sql4);
            $stmt4->execute();
            $data4 = $stmt4->fetchAll(\PDO::FETCH_ASSOC);

            // Procesar los datos para el gráfico de barras apiladas
            // Determinar el número de semanas del mes actual y los rangos de fechas
            // Calcular el domingo anterior (o igual) al primer día del mes
            $primerDiaMes = date('Y-m-01');
            $ultimoDiaMes = date('Y-m-t');
            $inicio = strtotime('last sunday', strtotime($primerDiaMes));
            if (date('w', strtotime($primerDiaMes)) == 0) {
                $inicio = strtotime($primerDiaMes); // Si ya es domingo
            }
            // Calcular el sábado siguiente (o igual) al último día del mes
            $fin = strtotime('next saturday', strtotime($ultimoDiaMes));
            if (date('w', strtotime($ultimoDiaMes)) == 6) {
                $fin = strtotime($ultimoDiaMes); // Si ya es sábado
            }
            $semanas = [];
            $ranges = []; // <-- nuevo array para los rangos de fechas
            while ($inicio <= $fin) {
                $inicioSemana = $inicio;
                $finSemana = strtotime('+6 days', $inicioSemana);
                $diaInicio = date('j', $inicioSemana);
                $mesInicio = date('M', $inicioSemana);
                $diaFin = date('j', $finSemana);
                $mesFin = date('M', $finSemana);
                if ($mesInicio === $mesFin) {
                    $etiqueta = "$diaInicio-$diaFin $mesInicio";
                } else {
                    $etiqueta = "$diaInicio $mesInicio - $diaFin $mesFin";
                }
                $semanas[] = $etiqueta;
                // Guardar el rango en formato YYYY-MM-DD
                $ranges[] = [
                    'start' => date('Y-m-d', $inicioSemana),
                    'end' => date('Y-m-d', $finSemana)
                ];
                $inicio = strtotime('+7 days', $inicioSemana);
            }
            $numSemanas = count($semanas);
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
            $colores = ['#3f4868', '#4b4b4b','#ffa500', '#007bff', '#28a745' ];
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
                'ranges' => $ranges, // <-- agregar aquí los rangos de fechas
            ];
            // Gráfica: Reporte de Fallas por Tipo de Falla (barras)
            $sqlFallasTipo = "SELECT tf.tipo_falla AS tipo, COUNT(rf.id_reporte_fallas) AS total
                FROM reporte_fallas rf
                JOIN tipo_falla tf ON rf.id_tipo_falla = tf.id_tipo_falla
                GROUP BY tf.tipo_falla
                ORDER BY total DESC;";
            $stmtFallasTipo = $this->db->prepare($sqlFallasTipo);
            $stmtFallasTipo->execute();
            $dataFallasTipo = $stmtFallasTipo->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'fallas_por_tipo',
                'title' => 'Reportes de Fallas por Tipo',
                'panel' => 5,
                'type' => 'bar',
                'labels' => array_column($dataFallasTipo, 'tipo'),
                'data' => array_column($dataFallasTipo, 'total'),
                'backgroundColor' => ['#3f4868', '#ffa500', '#007bff', '#28a745', '#4b4b4b'],
            ];

            // Gráfica: Estado de Equipos Informáticos (donut)
            $sqlEstadoEquipos = "SELECT eei.estado_equipo_informatico AS estado, COUNT(ei.id_equipo_informatico) AS total
                FROM equipo_informatico ei
                JOIN estado_equipo_informatico eei ON ei.id_estado_equipo = eei.id_estado_equipo_informatico
                WHERE eei.estado_equipo_informatico != 'Desincorporado'
                GROUP BY eei.estado_equipo_informatico
                ORDER BY FIELD(eei.estado_equipo_informatico, 'Operativo', 'En Reparación', 'Averiado');";
            $stmtEstadoEquipos = $this->db->prepare($sqlEstadoEquipos);
            $stmtEstadoEquipos->execute();
            $dataEstadoEquipos = $stmtEstadoEquipos->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'estado_equipos',
                'title' => 'Estado de Equipos Informáticos',
                'panel' => 6,
                'type' => 'doughnut',
                'labels' => array_column($dataEstadoEquipos, 'estado'),
                'data' => array_column($dataEstadoEquipos, 'total'),
                'backgroundColor' => ['#28a745', '#007bff', '#f44336'],
            ];
            // Gráfica: Prioridades de los Reportes de Fallas (barras horizontales)
            $sqlPrioridades = "SELECT prioridad, COUNT(*) AS total
                FROM reporte_fallas
                WHERE prioridad IS NOT NULL AND prioridad != ''
                GROUP BY prioridad
                ORDER BY FIELD(prioridad, 'Alta', 'Media', 'Baja');";
            $stmtPrioridades = $this->db->prepare($sqlPrioridades);
            $stmtPrioridades->execute();
            $dataPrioridades = $stmtPrioridades->fetchAll(\PDO::FETCH_ASSOC);
            // Asegurar que siempre estén las tres prioridades
            $prioridades = ['Alta', 'Media', 'Baja'];
            $totales = array_fill_keys($prioridades, 0);
            foreach ($dataPrioridades as $row) {
                $totales[$row['prioridad']] = (int)$row['total'];
            }
            $charts[] = [
                'id' => 'prioridades_fallas',
                'title' => 'Prioridad de los Reportes de Fallas',
                'panel' => 7,
                'type' => 'bar',
                'labels' => $prioridades,
                'data' => array_values($totales),
                'backgroundColor' => ['#f44336', '#ffc107', '#28a745'], // Verde, Amarillo, Rojo
                'horizontal' => true // Indicador para el frontend
            ];
            // Gráfica de líneas: Cantidad de actividades por mes (solo año actual, formato MM/YYYY)
            $sqlActividadesMes = "SELECT DATE_FORMAT(fecha_actividad, '%m/%Y') AS mes, COUNT(*) AS total
                FROM reporte_actividades
                WHERE YEAR(fecha_actividad) = YEAR(CURDATE())
                GROUP BY mes
                ORDER BY MIN(fecha_actividad) ASC;";
            $stmtActividadesMes = $this->db->prepare($sqlActividadesMes);
            $stmtActividadesMes->execute();
            $dataActividadesMes = $stmtActividadesMes->fetchAll(\PDO::FETCH_ASSOC);
            $charts[] = [
                'id' => 'actividades_por_mes',
                'title' => 'Actividades por Mes',
                'panel' => 8,
                'type' => 'line',
                'labels' => array_column($dataActividadesMes, 'mes'),
                'data' => array_column($dataActividadesMes, 'total'),
                'backgroundColor' => ['#1E90FF'],
                'borderColor' => ['#1E90FF'],
                'fill' => false,
                'legendDisplay' => false // Ocultar leyenda en el frontend
            ];

            // Agregar más consultas para otras gráficas si es necesario

            return $charts;
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener los datos: " . $e->getMessage());
        }
    }
}
