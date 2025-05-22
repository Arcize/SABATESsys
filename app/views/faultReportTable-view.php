<?php

use app\controllers\FaultTypeController;

$faultTypeController = new FaultTypeController();
$faultTypes = $faultTypeController->listFaultTypes();
?>

<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Reportes de Fallas</h3>
        <div class="table-actions">
            <?php if ($viewData['puede_reportar_falla']): ?>

                <button id="resetModal" class="table-button open-modal" data-target-modal="faultReportModal" data-fetch="false">Crear Reporte</button>
            <?php endif; ?>

        </div>
    </div>
    <table class="table row-border hover" id="faultReportTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Código</th>
                <th scope="col">Reportado Por</th>
                <th scope="col">Tipo de Falla</th>
                <!-- <th scope="col">ID Equipo</th> -->
                <th scope="col">Fecha del Reporte</th>
                <th scope="col">Estado</th>
                <th scope="col">Prioridad</th>
                <!-- <th scope="col">Fecha de la Falla</th> -->
                <th scope="col">Técnico Asignado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
        </tbody>
    </table>
</div>
<div class="overlay-modal close-modal" data-modal-id="faultReportOverlay">
    <div class="modal-box" id="faultReportModal" data-modal-id="faultReportModal">
        <div class="modal-header">
            <h3 class="h3">Crear Reporte de Falla</h3>
        </div>
        <form action="index.php?view=faultReport&action=faultReport_fetch_create" method="POST" class="form" formType="faultReport">
            <div class="modal-body">
                <div class="flexFaultReport">
                    <div class="gridFaultReport">
                        <input type="hidden" id="id_fault_report" name="id_reporte_fallas" class="inputKey">
                        <div class="inputGroup">
                            <label for="fecha_falla">¿Qué día ocurrió la falla?</label>
                            <input class="input" id="fecha_falla" required type="date" name="fecha_falla">
                        </div>
                        <div class="inputGroup">
                            <label for="tipoFalla">Tipo de Falla</label>
                            <select name="id_tipo_falla" id="tipoFalla" required class="input">
                                <option value="">Seleccione</option>
                                <?php foreach ($faultTypes as $faultType) : ?>
                                    <option value="<?php echo $faultType['id_tipo_falla']; ?>">
                                        <?php echo $faultType['tipo_falla']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="inputGroup textArea">
                    <label for="contentFaultReport">Descripción de la Falla:</label>
                    <textarea id="contentFaultReport" required name="contenido_reporte_fallas"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="overlay-modal close-modal" data-modal-id="faultReportOverlayOne">
    <div class="modal-box" id="faultReportModalOne" data-modal-id="faultReportModalOne">
        <?php
        // require_once '../app/views/faultReport-view.php';
        ?>
    </div>
</div>

<!-- Modal para Atender Reporte -->
<div class="overlay-modal close-modal" data-modal-id="attendReportOverlay">
    <div class="modal-box" id="attendReportModal" data-modal-id="attendReportModal">
        <div class="modal-header">
            <h3 class="h3">Atender Reporte</h3>
        </div>
        <form id="attendReportForm">
            <div class="modal-body">
                <input type="hidden" id="attend_report_id" name="id_reporte_fallas">
                <div class="inputGroup">
                    <label for="attend_observacion">Observación:</label>
                    <textarea id="attend_observacion" name="observacion" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>
<div class="overlay-modal close-modal" data-modal-id="rejectReportOverlay">
    <div class="modal-box" id="rejectReportModal" data-modal-id="rejectReportModal">
        <div class="modal-header">
            <h3 class="h3">Atender Reporte</h3>
        </div>
        <form id="attendReportForm">
            <div class="modal-body">
                <input type="hidden" id="attend_report_id" name="id_reporte_fallas">
                <div class="inputGroup">
                    <label for="attend_observacion">Observación:</label>
                    <textarea id="attend_observacion" name="observacion" required></textarea>
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
    document.addEventListener('DOMContentLoaded', function() {
        const tipoFallaSelect = document.getElementById('tipoFalla');
        const userDetailsContainer = document.querySelector('.gridFaultReport');
        const faultReportModal = document.getElementById('faultReportModal');
        const closeModalButtons = faultReportModal.querySelectorAll('.close-modal');
        const flexFaultReport = document.querySelector('.flexFaultReport');

        // Función para crear los campos dinámicamente
        function createFields() {
            // Crear el campo de Cédula del Usuario
            const cedulaInputGroup = document.createElement('div');
            cedulaInputGroup.classList.add('inputGroup');
            cedulaInputGroup.innerHTML = `
                <label for="cedulaPC">Cédula del Usuario:</label>
                <input class="input ci" id="cedulaPC" required type="text" name="cedula" onkeyup="crossFields();">
            `;
            userDetailsContainer.appendChild(cedulaInputGroup);

            // Crear el campo de ID del Equipo
            const idPCInputGroup = document.createElement('div');
            idPCInputGroup.classList.add('inputGroup');
            idPCInputGroup.innerHTML = `
                <label for="idPC">ID del Equipo:</label>
                <input class="input numbers" id="idPC" required type="text" name="id_equipo_informatico" onkeyup="crossFields();">
            `;
            userDetailsContainer.appendChild(idPCInputGroup);
        }

        // Función para eliminar los campos dinámicamente
        function destroyFields() {
            const cedulaInput = document.getElementById('cedulaPC');
            const idPCInput = document.getElementById('idPC');
            const pcDetails = document.querySelector('.pcDetails');
            if (cedulaInput) {
                cedulaInput.parentElement.remove();
            }
            if (idPCInput) {
                idPCInput.parentElement.remove();
            }
            if (pcDetails) {
                pcDetails.remove(); // Eliminar el contenedor de detalles del PC
            }
        }

        function createDetails() {
            const pcDetails = document.createElement('div');
            pcDetails.classList.add('pcDetails');
            pcDetails.innerHTML = `
                <div><span>Fabricante: </span><span></span></div>
                <div><span>Procesador: </span><span></span></div>
                <div><span>Motherboard: </span><span></span></div>
                <div><span>Fuente: </span><span></span></div>
                <div><span>RAM: </span><span></span></div>
                <div><span>Almacenamiento: </span><span></span></div>
                `;
            flexFaultReport.appendChild(pcDetails); // Agregar el contenedor al DOM
        }

        // Función para restablecer el estado inicial
        function resetModal() {
            destroyFields(); // Eliminar los campos dinámicamente
            tipoFallaSelect.value = ""; // Restablecer el valor del select
        }

        // Ocultar los campos inicialmente
        resetModal();

        // Escuchar cambios en el select de tipo de falla
        tipoFallaSelect.addEventListener('change', function() {
            if (this.value === '1') { // Si el valor seleccionado es "1"
                createFields(); // Crear los campos dinámicamente
                createDetails(); // Crear detalles del PC
            } else {
                destroyFields(); // Eliminar los campos dinámicamente
            }
        });

        // Escuchar el evento de cerrar el modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', resetModal);
        });

        // También restablecer el modal al abrirlo
        document.getElementById("resetModal").addEventListener('click', resetModal);
    });
</script>
<script>
    // Pasar el valor de $_SESSION['username'] al cliente
    const currentId = "<?php echo $_SESSION['id_usuario']; ?>";
</script>
<script>
    $(document).ready(function() {
        $('#faultReportTable').DataTable({
            ...commonDatatableConfig,
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                filename: 'Reporte_Fallas_' + new Date().toISOString().slice(0, 10),
                customize: function(doc) {
                    // Si tienes un logo, puedes usarlo igual que en activitiesReportTable
                    const logoBase64 = '<?php echo isset($logoBase64) ? $logoBase64 : ""; ?>';
                    doc.header = function() {
                        return {
                            image: logoBase64,
                            width: 150,
                            alignment: 'center',
                            margin: [0, 20, 0, 0]
                        };
                    };

                    // Ocultar columnas no deseadas (por ejemplo, N° y Acciones)
                    const columnasOcultar = [7, 8]; // Ajusta los índices según tus columnas
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

                    // Título personalizado
                    doc.content.splice(0, 0, {
                        text: 'Reporte de Fallas',
                        style: 'header'
                    });
                    doc.styles = {
                        header: {
                            fontSize: 18,
                            bold: true,
                            margin: [0, 40, 0, 0]
                        }
                    };
                },
                orientation: 'portrait',
                pageSize: 'A4',
                titleAttr: 'Exportar la tabla actual a PDF'
            }],
            ajax: {
                url: 'index.php?view=faultReport&action=faultReport_fetch_page',
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
                    data: 'codigo_reporte_fallas'
                },
                {
                    data: 'usuario_reportante'
                },
                {
                    data: 'tipo_falla'
                },
                // {
                //     data: 'id_equipo_informatico',
                //     render: function(data, type, row) {
                //         // 'data' es el valor de la celda para esta columna y fila
                //         if (data === null || data === undefined) {
                //             return 'No Aplica'; // O cualquier otro texto/HTML que quieras mostrar
                //         } else {
                //             return data; // Muestra el valor original si no es null
                //         }
                //     }
                // },
                {
                    data: 'fecha_hora_reporte_fallas',
                    render: function(data, type, row) {
                        // Formatear la fecha y hora
                        const date = new Date(data);
                        return date.toLocaleString('es-ES', {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        }).replace(",", "");
                    }
                },
                {
                    data: 'estado_reporte_fallas',
                    render: function(data, type, row) {
                        if (row.id_estado_reporte_fallas === 1) {
                            return `<span class="states status-pending">${data}</span>`;
                        } else if (row.id_estado_reporte_fallas === 2) {
                            return `<span class="states status-accepted">${data}</span>`;
                        } else if (row.id_estado_reporte_fallas === 3) {
                            return `<span class="states status-completed">${data}</span>`;
                        } else {
                            return data; // Muestra el valor original si no es ninguno de los anteriores
                        }
                    }
                },
                {
                    data: 'prioridad',
                    render: function(data, type, row) {
                        if (data === "Baja") {
                            return `<span class="states green-button">${data}</span>`;
                        } else if (data === "Media") {
                            return `<span class="states yellow-button">${data}</span>`;
                        } else if (data === "Alta") {
                            return `<span class="states red-button">${data}</span>`;
                        } else {
                            return data; // Muestra el valor original si no es ninguno de los anteriores
                        }
                    }

                },
                // {
                //     data: 'fecha_falla',
                //     render: function(data, type, row) {
                //         // Formatear la fecha
                //         const date = new Date(data);
                //         return date.toLocaleDateString('es-ES', {
                //             year: 'numeric',
                //             month: '2-digit',
                //             day: '2-digit'
                //         });
                //     }
                // },
                {
                    data: 'tecnico_asignado',
                    render: function(data, type, row) {
                        // 'data' es el valor de la celda para esta columna y fila
                        if (data === null || data === undefined) {
                            return `<button class="table-button green-button accept-repair-button" 
                                        data-report-id="${row.id_reporte_fallas}" 
                                        onclick="acceptRepair(${row.id_reporte_fallas})">
                                        Aceptar
                                    </button>`;
                        } else if (data == currentId) {
                            return `<button class="table-button red-button reject-repair-button open-modal" 
                                        data-report-id="${row.id_reporte_fallas}" data-fetch="false" data-target-modal="rejectReportModal">
                                        Rechazar
                                    </button>`;
                        } else {
                            return row.tecnico_asignado_nombre; // Muestra el valor original si no es null
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let buttons = `<div class="actions-dropdown-container">
            <button class="crud-button actions-dropdown-btn dark-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>
            </button>
            <div class="actions-dropdown-menu" style="display:none; position:absolute; z-index:10;">
                <button class="crud-button crud-option open-modal" 
                    data-target-modal="faultReportModalOne" data-fetch="true">Ver Detalles
                </button>
                <button onclick='sentData(this)' class="crud-button crud-option generate-report-btn" 
                    data-row='${encodeURIComponent(row.id_reporte_fallas)}' type-row='${encodeURIComponent(row.id_tipo_falla)}'>
                    Generar Reporte
                </button>`;
                        // Agregar el botón "Atender" solo si el técnico asignado es el actual
                        if (row.tecnico_asignado == currentId) {
                            buttons += `
                <button class="crud-button crud-option attend-btn open-modal" data-target-modal="attendReportModal" data-fetch="false">
                    Atender
                </button>`;
                        }
                        buttons += `</div></div>`;
                        return buttons;
                    }
                },
            ]
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delegación de eventos para el menú desplegable en la tabla
        document.getElementById('faultReportTable').addEventListener('click', function(e) {
            // Si el click fue en el botón principal del dropdown
            if (e.target.closest('.actions-dropdown-btn')) {
                e.stopPropagation();
                // Cierra otros menús abiertos, excepto el actual
                const btn = e.target.closest('.actions-dropdown-btn');
                const menu = btn.parentElement.querySelector('.actions-dropdown-menu');
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.style.display = 'none';
                });
                // Alterna el menú actual
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            }
        });

        // Cierra el menú si se hace clic fuera
        document.addEventListener('click', function() {
            document.querySelectorAll('.actions-dropdown-menu').forEach(menu => menu.style.display = 'none');
        });
    });
</script>
<script>
    function sentData(btn) {
        // Obtiene el JSON serializado y decodificado
        const id = decodeURIComponent(btn.getAttribute('data-row'));
        const type = decodeURIComponent(btn.getAttribute('type-row'));
        console.log(id);
        fetch('index.php?view=faultReport&action=generateReport', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_fault_report=' + encodeURIComponent(id) + '&id_type_report=' + encodeURIComponent(type)
            })
            .then(response => response.json())
            .then(data => {
                // Crea un formulario oculto
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?view=faultReportV';

                // Crea un input oculto con el JSON
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'row';
                input.value = JSON.stringify(data);

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
                console.log('Reporte generado:', data);
            })
            .catch(error => {
                console.error('Error al generar el reporte:', error);
            });

    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fecha_falla");

        if (inputFecha) {
            // Obtener la fecha actual
            const fechaActual = new Date();

            // Calcular la fecha de un mes atrás
            const mesAnterior = new Date();
            mesAnterior.setMonth(fechaActual.getMonth() - 1);

            // Asegurar que la fecha mínima es válida incluso en meses con menos días
            if (mesAnterior.getDate() !== fechaActual.getDate()) {
                mesAnterior.setDate(1); // Ajuste para evitar errores en meses más cortos
            }

            // Establecer los límites de `min` y `max`
            inputFecha.min = mesAnterior.toISOString().split("T")[0]; // Inicio: Hace un mes
            inputFecha.max = fechaActual.toISOString().split("T")[0]; // Fin: Hoy

            // Validar la fecha ingresada
            inputFecha.addEventListener("change", function() {
                const fechaSeleccionada = new Date(this.value);

                if (fechaSeleccionada < mesAnterior || fechaSeleccionada > fechaActual) {
                    alert("La fecha debe estar dentro del último mes.");
                    this.value = ""; // Limpia el campo si la fecha es inválida
                }
            });
        }
    });
</script>