<?php
require_once('app/models/roleModel.php');

$roleModel = new roleModel();
$role = $roleModel->readAll();
?>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Roles</h3>
        <a href="index.php?view=roleForm">
            <button class="table-button">Añadir Rol </button>
        </a>
    </div>
    <table class="table">
        <thead class="table-head">
            <tr>
                <th scope="col">ID Rol</th>
                <th scope="col">Rol</th>
                <th scope="col">Permisos</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($role as $key => $item) { ?>
                <tr>
                    <td><?php echo $item["id_rol"] ?></td>
                    <td><?php echo $item["rol"] ?></td>
                    <td><?php echo $item["rol"] ?></td>
                    <td><i></i></td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</div>