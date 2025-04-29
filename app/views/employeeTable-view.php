<?php

use app\controllers\DepartmentController;

$departmentController = new DepartmentController();
$departments = $departmentController->listDepartments();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Empleados</h3>
        <div class="table-actions">
            <button class="table-button open-modal" data-modal="employeeModal" data-fetch="false">Añadir Empleado</button>
        </div>
    </div>
    <table class="table row-border hover" id="employeeTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cédula</th>
                <th scope="col">Departamento</th>
                <th scope="col">Sexo</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
            <!-- Aquí puedes cargar datos dinámicamente -->
        </tbody>
    </table>
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
                        <input class="input capitalize-first only-letters no-spaces" id="nombre" required type="text" name="nombre" maxlength="25">
                    </div>
                    <div class="inputGroup">
                        <label for="apellido">Apellido:</label>
                        <input class="input capitalize-first only-letters no-spaces" id="apellido" required type="text" name="apellido" maxlength="25">
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
    $(document).ready(function() {
        // Inicializa DataTables
        $('#employeeTable').DataTable({
            ...commonDatatableConfig, // Utiliza el spread operator para incluir la configuración común
            ajax: {
                url: 'index.php?view=employee&action=employee_fetch_page',
                dataSrc: ''
            },
            columns: [{
                    title: "N°",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                {
                    data: 'nombre'
                },
                {
                    data: 'apellido'
                },
                {
                    data: 'cedula'
                },
                {
                    data: 'nombre_departamento'
                },
                {
                    data: 'sexo'
                },
                {
                    data: null, // Para la columna de acciones
                    render: function(data, type, row) {
                        return `
                        <div class="button-container">
                            <button class="crud-button edit-button open-modal" data-fetch="true" data-id="${data.id_persona}">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                            </button>
                            <button class="crud-button delete-button" onclick="confirmDelete(${data.id_persona})">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                            </button>
                        </div>`;
                    }
                }
            ]
        });  });
    setInterval(function() {}, 5000); // Consulta cada 5 segundos
</script>