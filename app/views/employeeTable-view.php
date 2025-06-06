<?php

use app\controllers\DepartmentController;

$departmentController = new DepartmentController();
$departments = $departmentController->listDepartments();
?>

<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;
?>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Empleados</h3>
        <div class="table-actions">
            <a href="index.php?view=employeeInactiveTable">
                <button type="button" class="table-button dark-button">Ver Empleados Inactivos</button>
            </a>
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
                <!-- <th scope="col">Fecha de Nacimiento</th> -->
                <th scope="col">Sexo</th>
                <th scope="col">Estado</th>
                <th scope="col">Estatus de Registro</th>
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
                        <input class="input ci" id="cedula" required type="text" name="cedula" onkeyup="checkCedulaAvailability()" data-readonly-on-edit="true">
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
                        <select id="id_sexo" required name="id_sexo" data-readonly-on-edit="true">
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
        var urlParams = new URLSearchParams(window.location.search);
        var estatus = urlParams.get('estatus');
        var table = $('#employeeTable').DataTable({
            ...commonDatatableConfig,
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                filename: 'Reporte_Empleados_' + new Date().toISOString().slice(0, 10),
                customize: function(doc) {
                    // Logo igual que en faultReportTable
                    const logoBase64 = '<?php echo $logoBase64 ?? ""; ?>';
                    doc.header = function() {
                        return {
                            image: logoBase64,
                            width: 150,
                            alignment: 'center',
                            margin: [0, 20, 0, 0]
                        };
                    };

                    // Ocultar columnas no deseadas (por ejemplo, N° y Acciones)
                    const columnasOcultar = [7]; // Ajusta los índices según tus columnas (N° y Acciones)
                    columnasOcultar.forEach(index => {
                        if (doc.content[1].table.body[0] && doc.content[1].table.body[0][index]) {
                            doc.content[1].table.body[0][index].text = '';
                        }
                    });
                    doc.content[1].table.body.forEach((row, rowIndex) => {
                        if (rowIndex > 0) {
                            columnasOcultar.forEach(index => {
                                if (row && row[index]) {
                                    row[index].text = '';
                                }
                            });
                        }
                    });

                    const sabatesIndex = doc.content.findIndex(element => (
                        typeof element.text === 'string' && element.text.includes('SABATES') // Busca el texto específico
                    ));
                    if (sabatesIndex > -1) {
                        doc.content.splice(sabatesIndex, 1);
                    }
                    // Título personalizado
                    doc.content.splice(0, 0, {
                        text: 'Reporte de Empleados',
                        style: 'header'
                    });
                    doc.styles = {
                        header: {
                            fontSize: 18,
                            bold: true,
                            margin: [0, 40, 0, 0]
                        }
                    };
                    // --- FOOTER PERSONALIZADO ---
                    const fechaHora = new Date().toLocaleString('es-ES');
                    const anioActual = new Date().getFullYear();
                    doc.footer = function(currentPage, pageCount) {
                        return {
                            columns: [{
                                    text: 'SABATES ' + anioActual,
                                    alignment: 'left',
                                    margin: [40, 0, 0, 0],
                                    fontSize: 9,
                                    color: '#000000'
                                },
                                {
                                    text: 'Reporte Generado el: ' + fechaHora,
                                    alignment: 'center',
                                    fontSize: 9,
                                    color: '#000000'
                                },
                                {
                                    text: 'Página ' + currentPage.toString() + ' de ' + pageCount,
                                    alignment: 'right',
                                    margin: [0, 0, 40, 0],
                                    fontSize: 9,
                                    color: '#000000'
                                }
                            ],
                            margin: [0, 0, 0, 10]
                        };
                    };
                },
                orientation: 'portrait',
                pageSize: 'A4',
                titleAttr: 'Exportar la tabla actual a PDF'
            }],
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
                    data: 'estado_empleado',
                    render: function(data, type, row) {
                        let colorClass = '';
                        if (data === 'Activo') {
                            colorClass = 'circle-green';
                        } else if (data === 'Inactivo') {
                            colorClass = 'circle-red';
                        }
                        return `
                            <span class="states ${colorClass}">${data}</span>
                        `;
                    }
                },
                {
                    data: 'id_usuario',
                    render: function(data, type, row) {
                        if (data === undefined || data === null) {
                            return '<span class="states dark-button">Sin Registrar</span>';
                        } else if (data !== null) {
                            return '<span class="states green-button">Registrado</span>';
                        }
                        return `
                            <span class="states ${colorClass}">${data}</span>
                        `;
                    }
                },
                {
                    data: null, // Para la columna de acciones
                    render: function(data, type, row) {
                        let isAdmin = row.rol && row.rol.toLowerCase() === 'administrador';
                        let actionBtns = `
                        <div class="actions-dropdown-container">
                            <button class="crud-button actions-dropdown-btn dark-button">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>
                            </button>
                            <div class="actions-dropdown-menu" style="display:none; position:absolute; z-index:10;">
                                <button class="crud-button crud-option open-modal"
                                    data-target-modal="employeeModal" data-fetch="true"
                                    data-id="${data.id_persona}">
                                    Editar
                                </button>`;
                        if (row.estado_empleado === 'Activo' && !isAdmin) {
                            actionBtns += `
                                <button class="crud-button crud-option deactivate-employee-btn"
                                    data-id="${data.id_persona}">
                                    Desactivar
                                </button>`;
                        } else if (row.estado_empleado === 'Inactivo') {
                            actionBtns += `
                                <button class="crud-button crud-option activate-employee-btn"
                                    data-id="${data.id_persona}">
                                    Activar
                                </button>`;
                        }
                        // Botón de generar reporte
                        actionBtns += `
                            <button class="crud-button crud-option generate-employee-report-btn"
                                data-row='${JSON.stringify(data)}'>
                                Generar Reporte
                            </button>
                        `;
                        actionBtns += `
                            </div>
                        </div>`;
                        return actionBtns;
                    }
                }
            ],
            initComplete: function() {
                // Aplicar el filtro si el parámetro 'estatus' existe
                if (estatus) {
                    this.api().search(estatus).draw();
                }
            }
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
            if (window.Swal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha inválida',
                    text: 'Debes ingresar una fecha de nacimiento de hace al menos 18 años.',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        popup: 'custom-swal-font'
                    }
                });
            } else {
                alert("Debes ingresar una fecha de nacimiento de hace al menos 18 años.");
            }
            this.value = ""; // Limpia el campo si la fecha es inválida
        }
    });
</script>
<script>
    // Dropdown de acciones y activar/desactivar empleado
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('employeeTable').addEventListener('click', function(e) {
            // Dropdown
            if (e.target.closest('.actions-dropdown-btn')) {
                e.stopPropagation();
                const btn = e.target.closest('.actions-dropdown-btn');
                const menu = btn.parentElement.querySelector('.actions-dropdown-menu');
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.style.display = 'none';
                });
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            }
            // Desactivar empleado
            const btnDeactivate = e.target.closest('.deactivate-employee-btn');
            if (btnDeactivate) {
                e.preventDefault();
                const id = btnDeactivate.getAttribute('data-id');
                if (window.Swal) {
                    Swal.fire({
                        title: '¿Está seguro?',
                        text: '¿Desea desactivar este empleado?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, desactivar',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`index.php?view=employee&action=employee_deactivate`, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/x-www-form-urlencoded"
                                    },
                                    body: `id_persona=${encodeURIComponent(id)}`
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Desactivado', data.message, 'success');
                                        $('#employeeTable').DataTable().ajax.reload(null, false);
                                    } else {
                                        Swal.fire('Error', data.message || 'No se pudo desactivar.', 'error');
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                                });
                        }
                    });
                }
            }
            // Activar empleado
            const btnActivate = e.target.closest('.activate-employee-btn');
            if (btnActivate) {
                e.preventDefault();
                const id = btnActivate.getAttribute('data-id');
                if (window.Swal) {
                    Swal.fire({
                        title: '¿Está seguro?',
                        text: '¿Desea activar este empleado?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, activar',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`index.php?view=employee&action=employee_activate`, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/x-www-form-urlencoded"
                                    },
                                    body: `id_persona=${encodeURIComponent(id)}`
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Activado', data.message, 'success');
                                        $('#employeeTable').DataTable().ajax.reload(null, false);
                                    } else {
                                        Swal.fire('Error', data.message || 'No se pudo activar.', 'error');
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                                });
                        }
                    });
                }
            }
        });
        document.addEventListener('click', function() {
            document.querySelectorAll('.actions-dropdown-menu').forEach(menu => menu.style.display = 'none');
        });
    });
</script>
<script>
    // Script para generar el reporte de empleado
    $(document).ready(function() {
        document.getElementById('employeeTable').addEventListener('click', function(e) {
            const btnReport = e.target.closest('.generate-employee-report-btn');
            if (btnReport) {
                e.preventDefault();
                const rowData = JSON.parse(btnReport.getAttribute('data-row'));
                // Petición al backend para obtener los datos completos del empleado
                fetch('index.php?view=employee&action=generateReport', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_persona=' + encodeURIComponent(rowData.id_persona)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && !data.error) {
                            // Crear formulario oculto y enviarlo a employeeV-view.php
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'index.php?view=employeeV';
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'row';
                            input.value = JSON.stringify(data);
                            form.appendChild(input);
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.error || 'No se pudo generar el reporte.'
                                });
                            } else {
                                alert(data.error || 'No se pudo generar el reporte.');
                            }
                        }
                    })
                    .catch(error => {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo generar el reporte.'
                            });
                        } else {
                            alert('No se pudo generar el reporte.');
                        }
                    });
            }
        });
    });
</script>