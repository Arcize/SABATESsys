<?php
// Vista: employeeInactiveTable-view.php
use app\controllers\DepartmentController;

$departmentController = new DepartmentController();
$departments = $departmentController->listDepartments();
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;
?>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Empleados Inactivos</h3>
        <div class="table-actions">
            <a href="index.php?view=employeeTable">
                <button type="button" class="table-button dark-button">Ver Empleados Activos</button>
            </a>
        </div>
    </div>
    <table class="table row-border hover" id="employeeInactiveTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cédula</th>
                <th scope="col">Departamento</th>
                <th scope="col">Sexo</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body-inactive" class="table-body">
            <!-- Los datos se cargarán dinámicamente -->
        </tbody>
    </table>
</div>
<script src="./js/datatableConfig.js"></script>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#employeeInactiveTable')) {
            $('#employeeInactiveTable').DataTable().destroy();
        }
        $('#employeeInactiveTable').DataTable({
            ...commonDatatableConfig,
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                filename: 'Reporte_Empleados_Inactivos_' + new Date().toISOString().slice(0, 10),
                customize: function(doc) {
                    const logoBase64 = '<?php echo $logoBase64 ?? ""; ?>';
                    doc.header = function() {
                        return {
                            image: logoBase64,
                            width: 150,
                            alignment: 'center',
                            margin: [0, 20, 0, 0]
                        };
                    };
                    const columnasOcultar = [7];
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
                        typeof element.text === 'string' && element.text.includes('SABATES')
                    ));
                    if (sabatesIndex > -1) {
                        doc.content.splice(sabatesIndex, 1);
                    }
                    doc.content.splice(0, 0, {
                        text: 'Reporte de Empleados Inactivos',
                        style: 'header'
                    });
                    doc.styles = {
                        header: {
                            fontSize: 18,
                            bold: true,
                            margin: [0, 40, 0, 0]
                        }
                    };
                    const fechaHora = new Date().toLocaleString('es-ES');
                    const anioActual = new Date().getFullYear();
                    doc.footer = function(currentPage, pageCount) {
                        return {
                            columns: [
                                {
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
                url: 'index.php?view=employee&action=employee_fetch_inactive',
                dataSrc: ''
            },
            columns: [
                {
                    title: "N°",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: 'nombre' },
                { data: 'apellido' },
                { data: 'cedula' },
                { data: 'nombre_departamento' },
                { data: 'sexo' },
                {
                    data: 'estado_empleado',
                    render: function(data, type, row) {
                        let colorClass = (data === 'Activo') ? 'circle-green' : 'circle-red';
                        return `<span class="states ${colorClass}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
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
                        if (row.estado_empleado === 'Inactivo') {
                            actionBtns += `
                                <button class="crud-button crud-option activate-employee-btn"
                                    data-id="${data.id_persona}">
                                    Activar
                                </button>`;
                        }
                        actionBtns += `
                                <button class="crud-button crud-option generate-employee-report-btn"
                                    data-row='${JSON.stringify(data)}'>
                                    Generar Reporte
                                </button>
                            </div>
                        </div>`;
                        return actionBtns;
                    }
                }
            ]
        });
    });
    // Dropdown de acciones igual que en la tabla principal
    $(document).ready(function() {
        document.getElementById('employeeInactiveTable').addEventListener('click', function(e) {
            if (e.target.closest('.actions-dropdown-btn')) {
                e.stopPropagation();
                const btn = e.target.closest('.actions-dropdown-btn');
                const menu = btn.parentElement.querySelector('.actions-dropdown-menu');
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.style.display = 'none';
                });
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            }
        });
        document.addEventListener('click', function() {
            document.querySelectorAll('.actions-dropdown-menu').forEach(menu => menu.style.display = 'none');
        });
    });
    // Activar empleado desde la tabla de inactivos
    $(document).on('click', '.activate-employee-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
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
                                $('#employeeInactiveTable').DataTable().ajax.reload(null, false);
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
    });
    // Generar reporte desde la tabla de inactivos
    $(document).ready(function() {
        document.getElementById('employeeInactiveTable').addEventListener('click', function(e) {
            const btnReport = e.target.closest('.generate-employee-report-btn');
            if (btnReport) {
                e.preventDefault();
                const rowData = JSON.parse(btnReport.getAttribute('data-row'));
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