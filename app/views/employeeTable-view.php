<?php

use app\controllers\DepartmentController;

$departmentController = new DepartmentController();
$departments = $departmentController->listDepartments();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Empleados</h3>
        <div class="table-actions">
            <button class="table-button open-modal" data-target-modal="employeeModal" data-fetch="false">Añadir Empleado</button>
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
<div class="overlay-modal" data-modal-id="employeeModalOverlay">
    <div class="modal-box" id="employeeModal" data-modal-id="employeeModal">
        <div class="modal-header">
            <h3 class="h3">Registrar un empleado</h3>
        </div>
        <form action="index.php?view=employee&action=employee_fetch_create" method="POST" class="form" formType="employee">
            <div class="modal-body">
                <input type="hidden" id="id_persona" name="id_persona" class="inputKey">
                <div class="form-row">

                    <div class="inputGroup flex-item">
                        <label for="cedula">Cédula:</label>
                        <input class="input ci" id="cedula" required type="text" name="cedula" onkeyup="checkCedulaAvailability()">
                        <span class="cedulaError inputError"></span>
                    </div>
                    <div class="inputGroup flex-item">
                        <label for="id_departamento">Departamento:</label>
                        <select id="id_departamento" required name="id_departamento">
                            <option value="">Seleccione</option>
                            <?php foreach ($departments as $department) : ?>
                                <option value="<?php echo $department['id_departamento']; ?>"><?php echo $department['nombre_departamento']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="inputGroup flex-item">
                        <label for="nombre">Nombre:</label>
                        <input class="input capitalize-first only-letters no-spaces" id="nombre" required type="text" name="nombre" maxlength="25">
                    </div>
                    <div class="inputGroup flex-item">
                        <label for="apellido">Apellido:</label>
                        <input class="input capitalize-first only-letters no-spaces" id="apellido" required type="text" name="apellido" maxlength="25">
                    </div>
                </div>
                <div class="form-row">
                    <div class="inputGroup flex-item">
                        <label for="correo">Email:</label>
                        <input class="input lowercase validate-email" id="correo" required type="text" name="correo" maxlength="100">
                        <span class="emailError inputError"></span>

                    </div>
                    <div class="inputGroup flex-item">
                        <label for="id_sexo">Sexo:</label>
                        <select id="id_sexo" required name="id_sexo">
                            <option value="">Seleccione</option>
                            <option value="1">Masculino</option>
                            <option value="2">Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="inputGroup flex-item">
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
                            <button class="crud-button green-button open-modal" data-fetch="true" data-id="${data.id_persona}" data-target-modal="employeeModal">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                            </button>
                        </div>`;
                    }
                }
            ]
        });
    });
    setInterval(function() {}, 5000); // Consulta cada 5 segundos
</script>
<script>
    const inputFechaNacimiento = document.getElementById("fecha_nac");

    // Obtener la fecha actual
    const hoy = new Date();

    // Calcular la fecha límite (hace 18 años)
    const hace18Anios = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());

    // Establecer el máximo permitido en el campo de fecha
    inputFechaNacimiento.max = hace18Anios.toISOString().split("T")[0];

    // Validar la fecha ingresada
    inputFechaNacimiento.addEventListener("change", function() {
        const fechaSeleccionada = new Date(this.value);

        if (fechaSeleccionada > hace18Anios) {
            alert("Debes ingresar una fecha de nacimiento de hace al menos 18 años.");
            this.value = ""; // Limpia el campo si la fecha es inválida
        }
    });
</script>