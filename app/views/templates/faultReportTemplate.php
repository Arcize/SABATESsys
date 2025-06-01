<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Vista Previa del Reporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        * {
            box-sizing: border-box;
            outline: none;
        }

        .report_header {
            top: 0;
            width: 100%;
            height: 20mm;
            text-align: center;
            padding: 10px;
        }

        .report_header img {
            height: 100%;
            width: auto;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
            padding: 10px;
            background-color: #f1f1f1;
        }

        .h2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 24px;
            margin: 10px 0;
        }

        .h3 {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 400;
            font-size: 16px;
            margin: 10px 0;
            color: black;
        }

        .a4-page {
            width: 210mm;
            height: auto;
            padding: 20mm;
            margin: auto;
            border: 1px solid #ccc;
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            table-layout: fixed
        }

        .table_report_header {
            border-left: 8px solid var(--color-primario);
            background: #e0e0e0;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        .btn-container {
            margin-top: 20px;
        }

        .table_report_text {
            text-align: justify;
            line-height: 1.5;
        }

        .volver-btn,
        .pdf-btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin: 5px;
            border-radius: 5px;
        }

        .timeline-vertical {
            position: relative;
            margin: 30px 0 30px 30px;
            padding-left: 20px;
            border-left: 3px solid #0077b6;
        }

        .timeline-event {
            position: relative;
            margin-bottom: 6px;
            padding-left: 20px;
            border-left: 3px solid #0077b6;
        }

        .timeline-content {
            margin-left: 20px;
        }

        .timeline-date {
            font-size: 13px;
            color: #888;
            margin-bottom: 2px;
        }

        .timeline-desc {
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 10px;
        }


        .states {
            color: #f6f6f6 !important;
            padding: 4px 8px;
            border-radius: 0.375rem;
            text-align: center;
        }

        .status-pending {
            background-color: #ffa500;
        }

        .status-accepted {
            background-color: #007BFF;
        }

        .status-completed {
            background-color: #28A745;
        }

        /* Solo para el timeline */
        .timeline-pending {
            color: #ffa500;
            background: none;
        }

        .timeline-accepted {
            color: #007BFF;
            background: none;
        }

        .timeline-completed {
            color: #28A745;
            background: none;
        }

        .green-button {
            background-color: #4caf50;
        }

        .red-button {
            background-color: #f44336;
        }

        .yellow-button {
            background-color: #ffc107;
        }

        .green-text {
            color: #4caf50;
        }

        .red-text {
            color: #f44336;
        }

        .yellow-text {
            color: #ffc107;
        }
    </style>
</head>
<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$imagenSrc = 'data:image/png;base64,' . $imagenData;
?>

<body>
    <div class="report_header">
        <img src="<?php echo $imagenSrc; ?>" alt="">
    </div>
    <?php
    // Determinar la clase del estado antes de imprimir el HTML
    $estadoClase = match ($row['id_estado_reporte_fallas'] ?? null) {
        1 => 'status-pending',
        2 => 'status-accepted',
        3 => 'status-completed',
        default => 'state-unknown',
    };

    // Determinar la clase de prioridad antes de imprimir el HTML
    $prioridad = $row['prioridad'] ?? '';
    switch ($prioridad) {
        case 'Baja':
            $prioridadClase = 'green-button';
            break;
        case 'Media':
            $prioridadClase = 'yellow-button';
            break;
        case 'Alta':
            $prioridadClase = 'red-button';
            break;
        default:
            $prioridadClase = '';
    }

    function fecha_espanol($fecha)
    {
        if (!$fecha) return '';
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre'
        ];
        $dt = new DateTime($fecha);
        // Verifica si la fecha tiene hora (contiene espacio y dos puntos)
        if (strpos($fecha, ' ') !== false && strpos($fecha, ':') !== false) {
            $formato = $dt->format('d \d\e F \d\e Y, h:i:s a');
        } else {
            $formato = $dt->format('d \d\e F \d\e Y');
        }
        return strtr($formato, $meses);
    }
    ?>
    <h2 class="h2">Detalles del Reporte de Falla</h2>
    <h3 class="h3">Código: <?= htmlspecialchars($row['codigo_reporte_fallas'] ?? '') ?></h3>
    <table>
        <tr>
            <td></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Información Básica</th>
        </tr>
        <tr>
            <th>Tipo de Falla:</th>
            <td><?= htmlspecialchars($row['tipo_falla'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Fecha de la Falla:</th>
            <td><?= fecha_espanol($row['fecha_falla'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Fecha del Reporte:</th>
            <td><?= fecha_espanol($row['fecha_hora_reporte_fallas'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Estado:</th>
            <td>
                <span class="states <?= $estadoClase ?>">
                    <?= htmlspecialchars($row['estado_reporte_fallas'] ?? '') ?>
                </span>
            </td>
        </tr>
        <tr>
            <th>Prioridad:</th>
            <td>
                <?php if ($prioridadClase): ?>
                    <span class="states <?= $prioridadClase ?>">
                        <?= htmlspecialchars($prioridad) ?>
                    </span>
                <?php else: ?>
                    <?= htmlspecialchars($prioridad) ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Descripción</th>
        </tr>
        <tr>
            <td colspan="2" class="table_report_text"><?= htmlspecialchars($row['contenido_reporte_fallas'] ?? '') ?></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Información del Reportante</th>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td><?= htmlspecialchars($row['nombre'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Apellido:</th>
            <td><?= htmlspecialchars($row['apellido'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Cédula:</th>
            <td><?= htmlspecialchars($row['cedula'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Departamento:</th>
            <td><?= htmlspecialchars($row['nombre_departamento'] ?? '') ?></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Línea de tiempo</th>
        </tr>

                    <?php foreach ($row['seguimiento'] as $evento): ?>
                        <?php
                        $estadoClaseTimeline = match ($evento['id_estado_reporte'] ?? null) {
                            1 => 'timeline-pending',
                            2 => 'timeline-accepted',
                            3 => 'timeline-completed',
                            default => '',
                        };
                        $accionLower = strtolower(urldecode($evento['accion'] ?? ''));
                        $esRechazo = str_contains($accionLower, 'rechazado');
                        $esCompletado = ($evento['id_estado_reporte'] == 3);
                        $mostrarMotivo = (
                            ($esRechazo || $esCompletado)
                            && !empty($evento['descripcion'])
                        );
                        ?>
    <tr>
        <td colspan="2">
            <div class="timeline-event">
                <div class="timeline-content" style="margin-left: 20px;">
                    <div class="timeline-date"><?= fecha_espanol($evento['fecha_seguimiento']) ?></div>
                    <div class="timeline-desc">
                        <strong><?= htmlspecialchars(urldecode($evento['accion'])) ?></strong>
                        <?php if ($evento['nombre_tecnico']): ?>
                            <br>
                            Técnico: <?= htmlspecialchars($evento['nombre_tecnico'] . ' ' . $evento['apellido_tecnico']) ?>
                        <?php endif; ?>
                        <br>
                        Estado: <span class="<?= $estadoClaseTimeline ?>">
                            <?= htmlspecialchars($evento['nombre_estado_reporte']) ?>
                        </span>
                        <?php if (!empty($evento['prioridad'])): ?>
                            <?php
                            // Determinar la clase de prioridad para el timeline
                            switch ($evento['prioridad']) {
                                case 'Baja':
                                    $prioridadClaseTimeline = 'green-text';
                                    break;
                                case 'Media':
                                    $prioridadClaseTimeline = 'yellow-text';
                                    break;
                                case 'Alta':
                                    $prioridadClaseTimeline = 'red-text';
                                    break;
                                default:
                                    $prioridadClaseTimeline = '';
                            }
                            ?>
                            <br>
                            Prioridad: <span class="<?= $prioridadClaseTimeline ?>">
                                <?= htmlspecialchars($evento['prioridad']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($mostrarMotivo): ?>
                            <br>
                            <span class="timeline-motivo">
                                <strong><?= $esRechazo ? 'Motivo del Rechazo:' : 'Observaciones:' ?></strong> <?= htmlspecialchars($evento['descripcion']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </td>
    </tr>
                    <?php endforeach; ?>
    </table>