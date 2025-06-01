<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Vista Previa del Equipo</title>
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
            font-size: 24px;
            margin: 10px 0;
        }

        .h3 {
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
            border-left: 8px solid #0077b6;
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

        .modulo-title {
            font-weight: bold;
            margin-top: 10px;
            background-color: #f1f1f1;
            padding: 8px;
        }

        .modulo-table {
            width: 95%;
        }

        .modulo-table th,
        .modulo-table td {
            padding: 8px;
        }
    </style>
</head>
<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$imagenSrc = 'data:image/png;base64,' . $imagenData;
function safe($v)
{
    return htmlspecialchars($v ?? '');
}
?>

<body>
    <div class="report_header">
        <img src="<?php echo $imagenSrc; ?>" alt="">
    </div>
    <h2 class="h2">Detalles del Equipo Informático</h2>
    <h3 class="h3">ID: <?= safe($row['id_equipo_informatico']) ?></h3>
    <table>
        <tr>
            <td></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Información General</th>
        </tr>
        <tr>
            <th>Fabricante:</th>
            <td><?= safe($row['fabricante_equipo_informatico']) ?></td>
        </tr>
        <tr>
            <th>Estado:</th>
            <td><?= safe($row['estado_equipo_informatico']) ?></td>
        </tr>
        <tr>
            <th>Asignado a:</th>
            <td>
                <?php if (!empty($row['nombre_completo'])): ?>
                    <?= safe($row['nombre_completo']) ?> (C.I: <?= safe($row['cedula_persona']) ?>)
                <?php else: ?>
                    Sin asignar
                <?php endif; ?>
            </td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Procesador</th>
        </tr>
        <tr>
            <th>Fabricante:</th>
            <td><?= safe($row['fabricante_procesador']) ?></td>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td><?= safe($row['nombre_procesador']) ?></td>
        </tr>
        <tr>
            <th>Núcleos:</th>
            <td><?= safe($row['nucleos']) ?></td>
        </tr>
        <tr>
            <th>Frecuencia:</th>
            <td><?= safe($row['frecuencia_procesador']) ?></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Motherboard</th>
        </tr>
        <tr>
            <th>Fabricante:</th>
            <td><?= safe($row['fabricante_motherboard']) ?></td>
        </tr>
        <tr>
            <th>Modelo:</th>
            <td><?= safe($row['modelo_motherboard']) ?></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Fuente</th>
        </tr>
        <tr>
            <th>Fabricante:</th>
            <td><?= safe($row['fabricante_fuente_poder']) ?></td>
        </tr>
        <tr>
            <th>Wattage:</th>
            <td><?= safe($row['wattage_fuente']) ?></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">RAM</th>
        </tr>
        <tr>
            <th>Tipo:</th>
            <td><?= safe($row['tipo_ram']) ?></td>
        </tr>
        <tr>
            <th>Capacidad Total:</th>
            <td><?= safe($row['capacidad_ram_total']) ?> GB</td>
        </tr>
        <tr>
            <td colspan="2">
                <?php if (!empty($row['ramData']) && is_array($row['ramData'])): ?>
                    <?php foreach ($row['ramData'] as $i => $ram): ?>
                        <div class="modulo-title">Módulo RAM <?= $i + 1 ?></div>
                        <table class="modulo-table">
                            <tr>
                                <th>Fabricante</th>
                                <th>Capacidad (GB)</th>
                                <th>Frecuencia (MHz)</th>
                            </tr>
                            <tr>
                                <td><?= safe($ram['fabricante_ram']) ?></td>
                                <td><?= safe($ram['capacidad_ram']) ?></td>
                                <td><?= safe($ram['frecuencia_ram']) ?></td>
                            </tr>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Almacenamiento</th>
        </tr>
        <tr>
            <th>Total:</th>
            <td>
                <?php
                // Suma total de almacenamiento
                $totalAlmacenamiento = 0;
                if (!empty($row['storageData']) && is_array($row['storageData'])) {
                    foreach ($row['storageData'] as $storage) {
                        $totalAlmacenamiento += (int)($storage['capacidad_almacenamiento'] ?? 0);
                    }
                }
                echo safe($totalAlmacenamiento) . ' GB';
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php if (!empty($row['storageData']) && is_array($row['storageData'])): ?>
                    <?php foreach ($row['storageData'] as $i => $storage): ?>
                        <div class="modulo-title">Módulo Almacenamiento <?= $i + 1 ?></div>
                        <table class="modulo-table">
                            <tr>
                                <th>Fabricante</th>
                                <th>Tipo</th>
                                <th>Capacidad (GB)</th>
                            </tr>
                            <tr>
                                <td><?= safe($storage['fabricante_almacenamiento']) ?></td>
                                <td><?= safe($storage['tipo_almacenamiento']) ?></td>
                                <td><?= safe($storage['capacidad_almacenamiento']) ?></td>
                            </tr>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</body>

</html>