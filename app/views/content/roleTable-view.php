<?php
require_once('app/models/roleModel.php');

$roleModel = new roleModel();
$role = $roleModel->readAll();
?>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Roles</h3>
            <button class="table-button open-modal" data-modal="roleModal">Añadir Rol </button>
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
    <div class="overlay-modal">
        <div class="modal-box" id="roleModal">
            <div class="modal-header">
                <h3 class="h3">Crea un rol</h3>
            </div>
            <div class="modal-body">
                <div class="modalRoleNameInput">
                    <label for="roleName">Nombre del Rol</label>
                    <input type="text" class="input" id="roleName" maxlength="30">
                </div>
                <div class="permissionsModule">
                    <div class="permissionsContainer">
                        <div class="permissionsHeader">
                            <h4 class="h4">Empleados</h4>
                        </div>
                        <div class="switches">
                            <div class="permission">
                                <h5 class="h5">Ver</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="EmployeeView">
                                    <label for="EmployeeView" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Crear</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="EmployeeCreate">
                                    <label for="EmployeeCreate" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Editar</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="EmployeeUpdate">
                                    <label for="EmployeeUpdate" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Eliminar</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="EmployeeDelete">
                                    <label for="EmployeeDelete" class="switch"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="permissionsContainer">
                        <div class="permissionsHeader">
                            <h4 class="h4">Pc</h4>
                        </div>
                        <div class="switches">
                            <div class="permission">
                                <h5 class="h5">Ver</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="pcView">
                                    <label for="pcView" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Crear</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="pcCreate">
                                    <label for="pcCreate" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Editar</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="pcUpdate">
                                    <label for="pcUpdate" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Eliminar</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="pcDelete">
                                    <label for="pcDelete" class="switch"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="permissionsContainer">
                        <div class="permissionsHeader">
                            <h4 class="h4">Usuarios</h4>
                        </div>
                        <div class="switches">
                            <div class="permission">
                                <h5 class="h5">Ver</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="userView">
                                    <label for="userView" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Crear</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="userCreate">
                                    <label for="userCreate" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Editar</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="userUpdate">
                                    <label for="userUpdate" class="switch"></label>
                                </div>
                            </div>
                            <div class="permission">
                                <h5 class="h5">Eliminar</h5>
                                <div class="switchModule">
                                    <input type="checkbox" id="userDelete">
                                    <label for="userDelete" class="switch"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal">Cancelar</button>
                <button class="modal-button">Guardar</button>
            </div>
        </div>
    </div>
</div>