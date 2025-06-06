<?php

?>
<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$imagenSrc = 'data:image/png;base64,' . $imagenData;
?>
<?php
// Función para mostrar la fecha en español
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
        min-height: 297mm;
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
        margin-bottom: 25px;
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

<div class="report_header">
    <img src="<?php echo $imagenSrc; ?>" alt="">
</div>
<div>
    <h2 class="h2">Reporte de Actividad</h2>
    <h3 class="h3">Código: <?= htmlspecialchars($row['codigo_reporte_actividades'] ?? '') ?></h3>

    <table>
        <tr>
            <td></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Información Básica</th>
        </tr>
        <tr>
            <th>Título:</th>
            <td><?= htmlspecialchars($row['titulo_reporte'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Fecha de Actividad:</th>
            <td><?= fecha_espanol($row['fecha_actividad'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Categoría:</th>
            <td><?= htmlspecialchars($row['categoria'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Reportado por:</th>
            <td><?= htmlspecialchars($row['nombre_completo'] ?? '') ?></td>
        </tr>
        <tr>
            <th rowspan="<?= !empty($row['participantes']) && is_array($row['participantes']) ? count($row['participantes']) : 1 ?>">Participantes:</th>
            <?php
            if (!empty($row['participantes']) && is_array($row['participantes'])) {
                $first = true;
                foreach ($row['participantes'] as $p) {
                    if ($first) {
                        echo '<td>' . htmlspecialchars(str_replace('+', ' ', $p)) . '</td></tr>';
                        $first = false;
                    } else {
                        echo '<tr><td>' . htmlspecialchars(str_replace('+', ' ', $p)) . '</td></tr>';
                    }
                }
            } else {
                echo '<td></td></tr>';
            }
            ?>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Descripción</th>
        </tr>
        <tr>

            <td colspan="2 " class="table_report_text">
                <?= nl2br(htmlspecialchars($row['contenido_reporte'] ?? '')) ?>
            </td>
        </tr>
        <?php if (!empty($row['imagenes']) && is_array($row['imagenes'])): ?>
            <tr class="table_report_header">
                <th colspan="2">Evidencia</th>
            </tr>
            <?php foreach ($row['imagenes'] as $img):
                $imgPath = '../public/' . $img;
                if (file_exists($imgPath)) {
                    $imgData = base64_encode(file_get_contents($imgPath));
                    $imgSrc = 'data:image/' . pathinfo($imgPath, PATHINFO_EXTENSION) . ';base64,' . $imgData;
                } else {
                    $imgSrc = '';
                }
            ?>
                <?php if ($imgSrc): ?>
                    <tr>
                        <td colspan="2" style="text-align:center;">
                            <div style="border:1px solid #ccc; padding:12px; background:#fafafa; max-width:320px; max-height:380px; display:inline-flex; align-items:center; justify-content:center; margin: 16px auto;">
                                <img src="<?= $imgSrc ?>" alt="Evidencia" style="max-width:300px; max-height:350px; object-fit:contain; display:block; margin:auto;" />
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <!-- Si necesitas mostrar imágenes, agrega aquí el bloque correspondiente -->
</div>