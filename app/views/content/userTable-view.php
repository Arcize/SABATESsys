<?php
require_once('app/models/userModel.php');

$userModel = new userModel();
$user = $userModel->readAll();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Usuarios</h3>
        <a href="index.php?view=roleTable">
            <button class="table-button">Gestionar Roles</button>
        </a>
    </div>
    <table class="table">
        <thead class="table-head">
            <tr>
                <th scope="col">ID Usuario</th>
                <th scope="col">Usuario</th>
                <th scope="col">Cédula</th>
                <th scope="col">Rol</th>
                <th scope="col"><i></i></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user as $key => $item) { ?>
                <tr>
                    <td><?php echo $item["id_usuario"]; ?></td>
                    <td><?php echo $item["username"]; ?></td>
                    <td><?php echo $item["cedula"]; ?></td>
                    <td><?php echo $item["rol"]; ?></td>
                    <td class="relative-container">
                        <div class="button-container">
                            <div>
                                <a href="">
                                    <button class="crud-button edit-button">
                                        <img src="app/views/img/edit.svg" alt="Edit">
                                    </button>
                                </a>
                            </div>
                            <div>
                                <a href="">
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