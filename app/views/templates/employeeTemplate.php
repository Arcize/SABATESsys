<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleado</title>
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
            table-layout: fixed;
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

        .h2 {
            font-size: 24px;
            margin: 10px 0;
        }

        .h3 {
            font-size: 16px;
            margin: 10px 0;
            color: black;
        }

        .states {
            color: #f6f6f6 !important;
            padding: 4px 8px;
            border-radius: 0.375rem;
            text-align: center;
        }

        .state-activo {
            background-color: #28A745;
        }

        .state-inactivo {
            background-color: #f44336;
        }
    </style>
</head>
<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$imagenSrc = 'data:image/png;base64,' . $imagenData;
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
    $formato = $dt->format('d \d\e F \d\e Y');
    return strtr($formato, $meses);
}
$estado = strtolower($row['estado_empleado'] ?? '');
$estadoClase = $estado === 'activo' ? 'state-activo' : 'state-inactivo';
?>

<body>
    <div class="report_header">
        <img src="<?php echo $imagenSrc; ?>" alt="">
    </div>
    <h2 class="h2">Reporte de Empleado</h2>
    <table>
        <tr>
            <td></td>
        </tr>
        <tr class="table_report_header">
            <th colspan="2">Información Básica</th>
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
        <tr>
            <th>Correo:</th>
            <td><?= htmlspecialchars($row['correo'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Sexo:</th>
            <td><?= htmlspecialchars($row['sexo'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Fecha de Nacimiento:</th>
            <td><?= fecha_espanol($row['fecha_nac'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Estado:</th>
            <td><span class="states <?= $estadoClase ?>"><?= htmlspecialchars($row['estado_empleado'] ?? '') ?></span></td>
        </tr>
    </table>
</body>

</html>