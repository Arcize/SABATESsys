<?php
include_once('app/controllers/departmentController.php');
$departmentController = new departmentController();
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
                            <img src="app/views/img/filter.svg" alt="Filter">
                        </button>
                        <div class="dropdown-content filter-dropdown-menu">
                            <a href="#" class="dropdown-item">Opción 1</a>
                            <a href="#" class="dropdown-item">Opción 2</a>
                            <a href="#" class="dropdown-item">Opción 3</a>
                        </div>
                    </div>
                </div>
                <input type="text" class="input search-input" placeholder="Buscar...">
                <button class="search-button btn"><img src="app/views/img/search.svg" alt="Search"></button>
            </div>
            <button class="table-button open-modal" data-modal="employeeModal" data-fetch="false">Añadir Empleado</button>
        </div>
    </div>
    <table class="table">
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
            <h3 class="h3">Registre un empleado</h3>
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