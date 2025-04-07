<?php
use app\models\PcModel;
$pcModel = new PcModel();
$pcs = $pcModel->readAll();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Equipos Informáticos</h3>
        <a href="index.php?view=pcForm">
            <button class="table-button">Añadir Equipo</button>
        </a>
    </div>
    <table class="table">
        <thead class="table-head">
            <tr>
                <th scope="col">ID Equipo</th>
                <th scope="col">Fabricante</th>
                <th scope="col">Estado</th>
                <th scope="col">Asignado a</th>
                <th scope="col">Procesador</th>
                <th scope="col">Motherboard</th>
                <th scope="col">Fuente</th>
                <th scope="col">RAM</th>
                <th scope="col">Almacenamiento</th>
                <th scope="col"><i></i></th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
            <?php foreach ($pcs as $key => $item) { ?>
                <tr class="table-row">
                    <td><?php echo $item["id_equipo_informatico"]; ?></td>
                    <td><?php echo $item["fabricante_equipo_informatico"]; ?></td>
                    <td><?php echo $item["estado_equipo_informatico"]; ?></td>
                    <td><?php echo $item["nombre"] . ' ' . $item["apellido"]; ?></td>
                    <td><?php echo $item["fabricante_procesador"] . ' ' . $item["nombre_procesador"]; ?></td>
                    <td><?php echo $item["fabricante_motherboard"] . ' ' . $item["modelo_motherboard"]; ?></td>
                    <td><?php echo $item["fabricante_fuente_poder"] . ' ' . $item["wattage"] . 'w'; ?></td>
                    <td><?php echo $item["capacidad_ram_total"] . 'Gb'; ?></td>
                    <td><?php echo $item["almacenamiento_total"] . 'Gb'; ?></td>
                    <td class="relative-container">
                        <div class="button-container">
                            <div>
                                <a href="index.php?view=pcFormEdit&action=pc_edit&id=<?php echo $item['id_equipo_informatico']; ?>">
                                    <button class="crud-button edit-button">
                                        <img src="app/views/img/edit.svg" alt="Edit">
                                    </button>
                                </a>
                            </div>
                            <div>
                                <a href="index.php?view=pc&action=pc_delete&id=<?php echo $item['id_equipo_informatico']; ?>">
                                    <button class="crud-button delete-button">
                                        <img src="app/views/img/delete.svg" alt="Delete">
                                    </button>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>