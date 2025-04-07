<?php

use app\controllers\DepartmentController;

$departmentController = new DepartmentController();
$departments = $departmentController->listDepartments();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Empleados</h3>
        <div class="table-actions">
            <div class="table-filters">
                <div class="search-filter">
                    <div class="filter-dropdown">
                        <button class="dropdown-btn btn filter-button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                                <path d="M440-240q-17 0-28.5-11.5T400-280q0-17 11.5-28.5T440-320h80q17 0 28.5 11.5T560-280q0 17-11.5 28.5T520-240h-80ZM280-440q-17 0-28.5-11.5T240-480q0-17 11.5-28.5T280-520h400q17 0 28.5 11.5T720-480q0 17-11.5 28.5T680-440H280ZM160-640q-17 0-28.5-11.5T120-680q0-17 11.5-28.5T160-720h640q17 0 28.5 11.5T840-680q0 17-11.5 28.5T800-640H160Z" />
                            </svg>
                        </button>
                        <div class="dropdown-content filter-dropdown-menu">
                            <a href="#" class="dropdown-item">Opción 1</a>
                            <a href="#" class="dropdown-item">Opción 2</a>
                            <a href="#" class="dropdown-item">Opción 3</a>
                        </div>
                    </div>
                </div>
                <input type="text" class="input search-input" placeholder="Buscar...">
                <button class="search-button btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>
            <button class="table-button open-modal" data-modal="employeeModal" data-fetch="false">Añadir Empleado</button>
        </div>
    </div>
    <table class="table" id="employeeTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cédula</th>
                <th scope="col">Departamento</th>
                <th scope="col">Sexo</th>
                <!-- <th scope="col">ID Usuario</th> -->
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">

        </tbody>
    </table>
    <div class="table-footer">
        <div class="page-buttons">
            <button class="pagination-button prev btn">Anterior</button>
            <div class="pages">
            </div>
            <button class="pagination-button next btn">Siguiente</button>
        </div>
    </div>
</div>
<div class="overlay-modal">
    <div class="modal-box" id="employeeModal">
        <div class="modal-header">
            <h3 class="h3">Registrar un empleado</h3>
        </div>
        <form action="index.php?view=employee&action=employee_fetch_create" method="POST" class="form" formType="employee">
            <div class="modal-body">
                <div class="userDetails">
                    <input type="hidden" id="id_persona" name="id_persona" class="inputKey">
                    <div class="inputGroup">
                        <label for="nombre">Nombre:</label>
                        <input class="input capitalize-first only-letters no-spaces" id="nombre" required type="text" name="nombre" maxlength="30">
                    </div>
                    <div class="inputGroup">
                        <label for="apellido">Apellido:</label>
                        <input class="input capitalize-first only-letters no-spaces" id="apellido" required type="text" name="apellido" maxlength="30">
                    </div>
                    <div class="inputGroup">
                        <label for="cedula">Cédula:</label>
                        <input class="input ci" id="cedula" required type="text" name="cedula" onkeyup="checkCedulaAvailability()">
                        <span class="cedulaError"></span>
                    </div>
                    <div class="inputGroup">
                        <label for="correo">Email:</label>
                        <input class="input lowercase" id="correo" required type="text" name="correo" maxlength="100">
                    </div>
                    <div class="inputGroup">
                        <label for="id_departamento">Departamento:</label>
                        <select id="id_departamento" required name="id_departamento">
                            <option value="">Seleccione</option>
                            <?php foreach ($departments as $department) : ?>
                                <option value="<?php echo $department['id_departamento']; ?>"><?php echo $department['nombre_departamento']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="id_sexo">Sexo:</label>
                        <select id="id_sexo" required name="id_sexo">
                            <option value="">Seleccione</option>
                            <option value="1">Masculino</option>
                            <option value="2">Femenino</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="fecha_nac">Fecha de Nacimiento:</label>
                        <input class="input date" id="fecha_nac" required type="date" name="fecha_nac">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>
<script>
    initializePagination("employeeTable", "employee");
</script>