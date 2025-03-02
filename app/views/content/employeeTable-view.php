<?php
require_once('app/models/employeeModel.php');

$employeeModel = new employeeModel();
$employee = $employeeModel->readAll();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Empleados</h3>
        <a href="index.php?view=employeeForm">
            <button class="table-button">Añadir Empleado</button>
        </a>
    </div>
    <table class="table">
        <thead class="table-head">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cédula</th>
                <th scope="col">Email</th>
                <th scope="col">Departamento</th>
                <th scope="col">Sexo</th>
                <th scope="col">ID Usuario</th>
                <th scope="col"><i></i></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employee as $key => $item) { ?>
                <tr>
                    <td><?php echo $item["id_persona"]; ?></td>
                    <td><?php echo $item["nombre"]; ?></td>
                    <td><?php echo $item["apellido"]; ?></td>
                    <td><?php echo $item["cedula"]; ?></td>
                    <td><?php echo $item["correo"]; ?></td>
                    <td><?php echo $item["nombre_departamento"]; ?></td>
                    <td>
                        <?php
                        switch ($item["sexo"]) {
                            case 'Masculino':
                                echo 'M';
                                break;
                            case 'Femenino':
                                echo 'F';
                                break;
                            default:
                                echo '';
                                break;
                        }
                        ?>
                    </td>
                    <td><?php echo !empty($item["id_usuario"]) ? $item["id_usuario"] : 'Sin Registrar'; ?></td>
                    <td class="relative-container">
                        <div class="button-container">
                            <div>
                                <a href="index.php?view=employeeFormEdit&action=employee_edit&id=<?php echo $item['id_persona']; ?>">
                                    <button class="crud-button edit-button">
                                        <img src="app/views/img/edit.svg" alt="Edit">
                                    </button>
                                </a>
                            </div>
                            <div>
                                <a href="index.php?view=employee&action=employee_delete&id=<?php echo $item['id_persona']; ?>">
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