<?php

use app\controllers\FaultTypeController;

$faultTypeController = new FaultTypeController();
$faultTypes = $faultTypeController->listFaultTypes();

// Obtener técnicos si el usuario es admin
$tecnicos = [];
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $userModel = new \app\models\UserModel();
    $allUsers = $userModel->readPage();
    foreach ($allUsers as $u) {
        if (isset($u['id_rol']) && $u['id_rol'] == 3) {
            $tecnicos[] = [
                'id_usuario' => $u['id_usuario'],
                'nombre' => $u['nombre_completo']
            ];
        }
    }
}
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
        <div class="modal-header">
            <h3 class="h3">Detalles del Reporte de Falla</h3>
        </div>
        <div class="modal-body" id="faultReportDetailsBody" style="display:flex; flex-direction:column; align-items:center;">
            <div style="text-align:center; padding:1em; color:#888;">Cargando detalles...</div>
        </div>
        <div class="modal-footer">
            <button class="modal-button close-modal" type="button">Cerrar</button>
        </div>
    </div>
</div>
<!-- Modal para Atender Reporte -->
<div class="overlay-modal close-modal" data-modal-id="attendReportOverlay">
    <div class="modal-box" id="attendReportModal" data-modal-id="attendReportModal">
        <div class="modal-header">
            <h3 class="h3">Atender Reporte</h3>
        </div>
        <form id="attendReportForm" action="index.php?view=faultReport&action=attend_report" method="POST">
            <div class="modal-body">
                <table class="details-table attend-details-table">
                    <tr>
                        <th class="details-label">Código</th>
                        <td class="details-value"><span id="attend_codigo_reporte"></span></td>
                    </tr>
                    <tr>
                        <th class="details-label">Fecha del Reporte</th>
                        <td class="details-value"><span id="attend_fecha_reporte"></span></td>
                    </tr>
                </table>
                <div class="inputGroup textArea">
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
            <h3 class="h3">Rechazar Reporte de Falla</h3>
        </div>
        <form id="rejectReportForm" action="index.php?view=faultReport&action=unassign_technician" method="POST">
            <div class="modal-body">
                <input type="hidden" id="reject_report_id" name="id_reporte_fallas">

                <div class="inputGroup textArea">
                    <label for="reject_observacion">¿Por qué desea rechazar este reporte?</label>
                    <textarea id="reject_observacion" name="observacion" required></textarea>

                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Rechazar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para asignar técnico (solo admin) -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
<div class="overlay-modal close-modal" data-modal-id="assignTechnicianOverlay">
    <div class="modal-box" id="assignTechnicianModal" data-modal-id="assignTechnicianModal">
        <div class="modal-header">
            <h3 class="h3">Asignar Técnico</h3>
        </div>
        <form id="assignTechnicianForm" action="index.php?view=faultReport&action=assign_technician" method="POST">
            <div class="modal-body">
                <input type="hidden" id="assign_report_id" name="report_id">
                <div class="inputGroup">
                    <label for="tecnico_select">Selecciona un técnico:</label>
                    <select id="tecnico_select" name="tecnico_id" required class="input">
                        <option value="">Seleccione</option>
                        <!-- Opciones se llenan por JS -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Asignar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para invalidar reporte (solo admin) -->
<div class="overlay-modal close-modal" data-modal-id="invalidateReportOverlay">
    <div class="modal-box" id="invalidateReportModal" data-modal-id="invalidateReportModal">
        <div class="modal-header">
            <h3 class="h3">Invalidar Reporte de Falla</h3>
        </div>
        <form id="invalidateReportForm" action="index.php?view=faultReport&action=invalidate_report" method="POST">
            <div class="modal-body">
                <input type="hidden" id="invalidate_report_id" name="id_reporte_fallas">
                <div class="inputGroup">
                    <label for="invalid_reason">Motivo de invalidación</label>
                    <select id="invalid_reason" name="invalid_reason" class="input" required>
                        <option value="">Seleccione</option>
                        <option value="Duplicidad">Duplicidad</option>
                        <option value="Inconsistencia">Inconsistencia</option>
                    </select>
                </div>
                <div class="inputGroup textArea">
                    <label for="invalid_observacion">Observación (opcional):</label>
                    <textarea id="invalid_observacion" name="invalid_observacion"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Invalidar</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
<script>
$(document).ready(function() {
    // --- Llenar select de técnicos dinámicamente ---
    function cargarTecnicos() {
        $.get('index.php?view=employee&action=get_technicians', function(data) {
            var select = $('#tecnico_select');
            select.empty();
            select.append('<option value="">Seleccione</option>');
            if (Array.isArray(data)) {
                data.forEach(function(tecnico) {
                    select.append('<option value="' + tecnico.id_usuario + '">' + tecnico.nombre + '</option>');
                });
            }
        }, 'json');
    }

    // Solo permitir asignar si no hay técnico asignado
    $(document).on('click', '.assign-technician-btn.open-modal', function(e) {
        const reportId = $(this).data('report-id');
        // Obtener datos de la fila
        const rowData = $('#faultReportTable').DataTable().row($(this).closest('tr')).data();
        if (rowData && rowData.tecnico_asignado) {
            Swal.fire({
                icon: 'info',
                title: 'Ya hay un técnico asignado',
                text: 'No se puede reasignar un técnico a este reporte.'
            });
            e.stopImmediatePropagation();
            return false;
        }
        $('#assign_report_id').val(reportId);
        cargarTecnicos();
    });

    // Botón para abrir modal de invalidar (solo admin)
    $(document).on('click', '.invalidate-report-btn', function(e) {
        const reportId = $(this).data('report-id');
        // Buscar en la fila si ya tiene técnico asignado
        const rowData = $('#faultReportTable').DataTable().row($(this).closest('tr')).data();
        if (rowData && rowData.tecnico_asignado) {
            e.preventDefault();
            e.stopImmediatePropagation();
            Swal.fire({
                icon: 'info',
                title: 'No permitido',
                text: 'No se puede invalidar un reporte que ya tiene un técnico asignado.'
            });
            return false;
        }
        $('#invalidate_report_id').val(reportId);
        // Solo aquí abrimos la modal manualmente si pasa la validación
        const overlay = document.querySelector('[data-modal-id="invalidateReportOverlay"]');
        const modal = document.getElementById('invalidateReportModal');
        if (overlay && modal) {
            overlay.classList.add('overlay-active', 'overlay-opening');
            modal.classList.add('modal-active', 'modal-opening');
            overlay.style.display = '';
        }
    });
});
</script>

<style>
    #faultReportDetailsBody {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }
    .details-modal-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }
    .details-code {
        font-weight: bold;
        font-size: 1.1em;
        margin-bottom: 8px;
        text-align: center;
        width: 100%;
    }
    .details-code-label {
        font-weight: normal;
    }
    .details-table {
        width: 100%;
        border-collapse: collapse;
    }
    .details-table th.details-label,
    .details-table td.details-value {
        text-align: left;
        padding: 8px 10px;
        vertical-align: top;
    }
    .details-table th.details-label {
        font-weight: bold;
        width: 38%;
        background: #f5f5f5;
    }
    .details-table td.details-value {
        width: 62%;
        background: #fff;
    }
    .details-description-title {
        font-weight: bold;
        margin-bottom: 4px;
        margin-top: 18px;
        text-align: left;
        width: 100%;
    }
    .details-description-content {
        width: 100%;
        min-height: 60px;
        max-height: 120px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px;
        background: #fafafa;
        font-size: 1rem;
        margin-bottom: 18px;
        box-sizing: border-box;
        text-align: left;
    }
    .details-error {
        color: red;
        text-align: center;
        padding: 1em;
    }
    .attend-info-row {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 12px;
    }
    .attend-info-item {
        display: flex;
        align-items: flex-start;
    }
    .attend-info-label {
        font-weight: bold;
        margin-bottom: 2px;
    }
    .attend-info-table {
        width: 100%;
        margin-bottom: 14px;
        border-collapse: collapse;
        border-spacing: 0 6px;
        table-layout: fixed;
        border: solid 1px #e0e0e0;
    }
    .attend-info-label {
        font-weight: bold;
        width: 160px;
        padding-right: 10px;
        vertical-align: top;
    }
    .attend-info-table td {
        padding: 2px 4px;
        vertical-align: top;
        width: 100%;
    }
    .attend-details-table {
        width: 100%;
        border-collapse: collapse;
    }
    .attend-details-table th.details-label,
    .attend-details-table td.details-value {
        text-align: left;
        padding: 8px 10px;
        vertical-align: top;
    }
    .attend-details-table th.details-label {
        font-weight: bold;
        width: 38%;
        background: #f5f5f5;
    }
    .attend-details-table td.details-value {
        width: 62%;
        background: #fff;
    }
</style>

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
        // Inicialización normal de DataTable
        var table = $('#faultReportTable').DataTable({
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
                    const sabatesIndex = doc.content.findIndex(element => (
                        typeof element.text === 'string' && element.text.includes('SABATES') // Busca el texto específico
                    ));
                    if (sabatesIndex > -1) {
                        doc.content.splice(sabatesIndex, 1);
                    }
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
                    // --- FOOTER PERSONALIZADO ---
                    const fechaHora = new Date().toLocaleString('es-ES');
                    const anioActual = new Date().getFullYear();
                    doc.footer = function(currentPage, pageCount) {
                        return {
                            columns: [
                                { text: 'SABATES ' + anioActual, alignment: 'left', margin: [40, 0, 0, 0], fontSize: 9, color: '#000000' },
                                { text: 'Reporte Generado el: ' + fechaHora, alignment: 'center', fontSize: 9, color: '#000000' },
                                { text: 'Página ' + currentPage.toString() + ' de ' + pageCount, alignment: 'right', margin: [0, 0, 40, 0], fontSize: 9, color: '#000000' }
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
                        // Si el reporte está completado, no mostrar botones de acción
                        if (row.id_estado_reporte_fallas === 3) {
                            return row.tecnico_asignado_nombre ? row.tecnico_asignado_nombre : '-';
                        }
                        // Si ya hay técnico asignado
                        if (row.tecnico_asignado) {
                            // Si el técnico asignado coincide con el usuario actual, mostrar solo el botón rechazar
                            if (row.tecnico_asignado == currentId) {
                                return `<button class="table-button red-button reject-repair-button open-modal" 
            data-report-id="${row.id_reporte_fallas}" data-fetch="false" data-target-modal="rejectReportModal">
            Rechazar
        </button>`;
                            }
                            // Si no coincide, solo mostrar el nombre
                            return row.tecnico_asignado_nombre ? row.tecnico_asignado_nombre : '-';
                        }
                        // Si es admin y no hay técnico asignado, mostrar botón Asignar Técnico
                        if (typeof window.isAdmin !== 'undefined' && window.isAdmin) {
                            return `<button class="table-button blue-button assign-technician-btn open-modal" 
                        data-report-id="${row.id_reporte_fallas}" 
                        data-target-modal="assignTechnicianModal" data-fetch="false">
                        Asignar Técnico
                    </button>`;
                        }
                        // Si es técnico y no hay técnico asignado, mostrar botón aceptar
                        if (data === null || data === undefined) {
                            return `<button class="table-button green-button accept-repair-button" 
                        data-report-id="${row.id_reporte_fallas}" 
                        onclick="acceptRepair(${row.id_reporte_fallas})">
                        Aceptar
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
                        // Si NO está completido, mostrar también el botón Atender si corresponde
                        if (row.id_estado_reporte_fallas !== 3 && row.tecnico_asignado == currentId) {
                            buttons += `
<button class="crud-button crud-option attend-btn open-modal" data-target-modal="attendReportModal" data-fetch="false" data-report-id="${row.id_reporte_fallas}">
    Atender
</button>`;
                        }
                        // Si es admin, mostrar botón invalidar
                        if (typeof window.isAdmin !== 'undefined' && window.isAdmin && row.id_estado_reporte_fallas !== 4 && row.id_estado_reporte_fallas !== 5) {
                            buttons += `
<button class="crud-button crud-option invalidate-report-btn" data-target-modal="invalidateReportModal" data-fetch="false" data-report-id="${row.id_reporte_fallas}">
    Invalidar
</button>`;
                        }
                        buttons += `</div></div>`;
                        return buttons;
                    }
                },
            ]
        });

        // Filtrar por código si viene en la URL
        const urlParams = new URLSearchParams(window.location.search);
        const codigo = urlParams.get('codigo');
        if (codigo) {
            table.search(codigo).draw();
        }
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
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha inválida',
                            text: 'La fecha debe estar dentro del último mes.',
                            confirmButtonText: 'Aceptar',
                            customClass: {
                                popup: 'swal2-popup'
                            }
                        }).then(() => {
                            this.value = '';
                        });
                    } else {
                        alert('La fecha debe estar dentro del último mes.');
                        this.value = '';
                    }
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        // --- Modal Rechazar Reporte ---
        // Al abrir la modal de rechazar, setear el id del reporte
        $(document).on('click', '.reject-repair-button.open-modal', function() {
            const reportId = $(this).data('report-id');
            // Validar si el reporte está completado antes de abrir el modal
            const rowData = $('#faultReportTable').DataTable().row($(this).closest('tr')).data();
            if (rowData && rowData.id_estado_reporte_fallas === 3) {
                Swal.fire({
                    icon: 'info',
                    title: 'Reporte Completado',
                    text: 'No se puede rechazar un reporte que ya está completado.'
                });
                return;
            }
            $('#reject_report_id').val(reportId);
        });

        $('#rejectReportForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = $(form).serialize();
            const sentBtn = form.querySelector('.sentBtn');
            if (sentBtn) sentBtn.disabled = true;
            $.post(form.action, formData, function(data) {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Éxito' : 'Error',
                    text: data.message
                });
                if (data.success) {
                    // Cerrar modal
                    const modal = document.getElementById('rejectReportModal');
                    const overlay = modal.closest('.overlay-modal');
                    if (overlay && modal) {
                        overlay.classList.remove('overlay-active', 'overlay-opening');
                        modal.classList.remove('modal-active', 'modal-opening');
                        overlay.style.display = 'none';
                    }
                    // Recargar tabla
                    if ($('#faultReportTable').length) {
                        $('#faultReportTable').DataTable().ajax.reload(null, false);
                    }
                }
            }, 'json').always(function() {
                if (sentBtn) sentBtn.disabled = false;
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // --- Modal Atender Reporte ---
        $(document).on('click', '.attend-btn.open-modal', function() {
            // Obtener el id del reporte de forma robusta
            let reportId = $(this).data('report-id');
            if (!reportId) {
                // Buscar en la fila si no viene en el botón
                const rowData = $('#faultReportTable').DataTable().row($(this).closest('tr')).data();
                if (rowData) {
                    reportId = rowData.id_reporte_fallas;
                }
            }
            // Validar estado
            const rowData = $('#faultReportTable').DataTable().row($(this).closest('tr')).data();
            if (rowData && rowData.id_estado_reporte_fallas === 3) {
                Swal.fire({
                    icon: 'info',
                    title: 'Reporte Completado',
                    text: 'No se puede atender un reporte que ya está completado.'
                });
                return;
            }
            $('#attend_report_id').val(reportId);
            // Mostrar código y fecha
            if(rowData) {
                $('#attend_codigo_reporte').text(rowData.codigo_reporte_fallas || '');
                // Formatear la fecha
                let fecha = rowData.fecha_hora_reporte_fallas;
                if (fecha) {
                    const date = new Date(fecha);
                    fecha = date.toLocaleString('es-ES', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    }).replace(",", "");
                }
                $('#attend_fecha_reporte').text(fecha || '');
            } else {
                $('#attend_codigo_reporte').text('');
                $('#attend_fecha_reporte').text('');
            }
        });

        $('#attendReportForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = $(form).serialize();
            const sentBtn = form.querySelector('.sentBtn');
            if (sentBtn) sentBtn.disabled = true;
            $.post(form.action, formData, function(data) {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Éxito' : 'Error',
                    text: data.message
                });
                if (data.success) {
                    // Cerrar modal
                    const modal = document.getElementById('attendReportModal');
                    const overlay = modal.closest('.overlay-modal');
                    if (overlay && modal) {
                        overlay.classList.remove('overlay-active', 'overlay-opening');
                        modal.classList.remove('modal-active', 'modal-opening');
                        overlay.style.display = 'none';
                    }
                    // Recargar tabla
                    if ($('#faultReportTable').length) {
                        $('#faultReportTable').DataTable().ajax.reload(null, false);
                    }
                }
            }, 'json').always(function() {
                if (sentBtn) sentBtn.disabled = false;
            });
        });
    });
</script>
<script>
    // --- MODAL VER DETALLES DE REPORTE DE FALLA ---
    $(document).ready(function() {
        $('#faultReportTable').on('click', '.open-modal[data-target-modal="faultReportModalOne"]', function(e) {
            e.preventDefault();
            const row = $(this).closest('tr').length ? $('#faultReportTable').DataTable().row($(this).closest('tr')).data() : null;
            if (!row) {
                $('#faultReportDetailsBody').html('<div style="color:red">No se pudo obtener el reporte.</div>');
                return;
            }
            // Petición AJAX para obtener detalles completos
            $.ajax({
                url: 'index.php?view=faultReport&action=generateReport',
                method: 'POST',
                data: {
                    id_fault_report: row.id_reporte_fallas,
                    id_type_report: row.id_tipo_falla
                },
                dataType: 'json',
                success: function(data) {
                    if (data && !data.error) {
                        let fechaFalla = data.fecha_falla || '';
                        if (fechaFalla) {
                            // Intentar formatear la fecha a dd/mm/yyyy
                            const fechaObj = new Date(fechaFalla);
                            if (!isNaN(fechaObj.getTime())) {
                                const dia = String(fechaObj.getDate()).padStart(2, '0');
                                const mes = String(fechaObj.getMonth() + 1).padStart(2, '0');
                                const anio = fechaObj.getFullYear();
                                fechaFalla = `${dia}/${mes}/${anio}`;
                            }
                        }
                        let html = `<div class='details-modal-container'>`;
                        html += `<div class='details-code'><span class='details-code-label'>Código:</span> ${data.codigo_reporte_fallas || ''}</div>`;
                        html += `<table class='details-table'>
    <tr><th class='details-label'>Tipo de Falla</th><td class='details-value'>${data.tipo_falla || ''}</td></tr>
    <tr><th class='details-label'>Fecha de la Falla</th><td class='details-value'>${fechaFalla}</td></tr>
    <tr><th class='details-label'>Estado</th><td class='details-value'>${data.estado_reporte_fallas || ''}</td></tr>
    <tr><th class='details-label'>Prioridad</th><td class='details-value'>${data.prioridad || ''}</td></tr>
    <tr><th class='details-label'>Reportado Por</th><td class='details-value'>${data.nombre || ''} ${data.apellido || ''}</td></tr>
    <tr><th class='details-label'>Cédula</th><td class='details-value'>${data.cedula || ''}</td></tr>
    <tr><th class='details-label'>Departamento</th><td class='details-value'>${data.nombre_departamento || ''}</td></tr>
</table>`;
                        html += `<div class='details-description-title'>Descripción</div>`;
                        html += `<div class='details-description-content'>${data.contenido_reporte_fallas || ''}</div>`;
                        html += `</div>`;
                        $('#faultReportDetailsBody').html(html);
                    } else {
                        $('#faultReportDetailsBody').html('<div class="details-error">No se pudo cargar el detalle.</div>');
                    }
                },
                error: function() {
                    $('#faultReportDetailsBody').html('<div class="details-error">Error de conexión.</div>');
                }
            });
        });
    });
</script>
<script>
    // Variable global para saber si es admin
    window.isAdmin = <?php echo (isset($_SESSION['role']) && $_SESSION['role'] == 1) ? 'true' : 'false'; ?>;

    $(document).ready(function() {
        // Abrir modal de asignar técnico y setear el id del reporte
        $(document).on('click', '.assign-technician-btn.open-modal', function() {
            const reportId = $(this).data('report-id');
            $('#assign_report_id').val(reportId);
        });

        // Enviar formulario de asignación de técnico
        $('#assignTechnicianForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = $(form).serialize();
            const sentBtn = form.querySelector('.sentBtn');
            if (sentBtn) sentBtn.disabled = true;
            $.post(form.action, formData, function(data) {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Éxito' : 'Error',
                    text: data.message
                });
                if (data.success) {
                    // Cerrar modal
                    const modal = document.getElementById('assignTechnicianModal');
                    const overlay = modal.closest('.overlay-modal');
                    if (overlay && modal) {
                        overlay.classList.remove('overlay-active', 'overlay-opening');
                        modal.classList.remove('modal-active', 'modal-opening');
                        overlay.style.display = 'none';
                    }
                    // Recargar tabla
                    if ($('#faultReportTable').length) {
                        $('#faultReportTable').DataTable().ajax.reload(null, false);
                    }
                }
            }, 'json').always(function() {
                if (sentBtn) sentBtn.disabled = false;
            });
        });
    });
</script>
<script>
    // Enviar formulario de invalidación
    $('#invalidateReportForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = $(form).serialize();
        const sentBtn = form.querySelector('.sentBtn');
        if (sentBtn) sentBtn.disabled = true;
        $.post(form.action, formData, function(data) {
        Swal.fire({
            icon: data.success ? 'success' : 'error',
            title: data.success ? 'Éxito' : 'Error',
            text: data.message
        });
        if (data.success) {
            // Cerrar modal
            const modal = document.getElementById('invalidateReportModal');
            const overlay = modal.closest('.overlay-modal');
            if (overlay && modal) {
                overlay.classList.remove('overlay-active', 'overlay-opening');
                modal.classList.remove('modal-active', 'modal-opening');
                overlay.style.display = 'none';
            }
            // Recargar tabla
            if ($('#faultReportTable').length) {
                $('#faultReportTable').DataTable().ajax.reload(null, false);
            }
        }
    }, 'json').always(function() {
        if (sentBtn) sentBtn.disabled = false;
    });
});
</script>