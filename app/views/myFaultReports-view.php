<?php
// misFaultReports-view.php
// Vista en español para "Mis Reportes de Fallas" para usuarios estándar

use app\controllers\FaultTypeController;

$faultTypeController = new FaultTypeController();
$faultTypes = $faultTypeController->listFaultTypes();

$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Mis Reportes de Fallas</h3>
        <div class="table-actions">
            <!-- Botón para crear nuevo reporte (igual que el original) -->
            <button class="table-button open-modal" data-target-modal="faultReportModal" data-fetch="false">
                Crear Reporte
            </button>
        </div>
    </div>
    <table class="table row-border hover" id="myFaultReportTable">
        <thead class="table-head">
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Tipo de Falla</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Técnico</th>
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
    // Inicialización de DataTable para "Mis Reportes de Fallas"
    var table = $('#myFaultReportTable').DataTable({
            ...commonDatatableConfig,
        buttons: [],
        ajax: {
            url: 'index.php?view=faultReport&action=faultReport_fetch_my_reports', // Debe devolver solo los reportes del usuario actual
            dataSrc: ''
        },
        columns: [
            {
                title: "#",
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { data: 'codigo_reporte_fallas', title: 'Código' },
            { data: 'tipo_falla', title: 'Tipo de Falla' },
            {
                data: 'fecha_hora_reporte_fallas',
                title: 'Fecha',
                render: function(data) {
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
                title: 'Estado',
                render: function(data, type, row) {
                    if (row.id_estado_reporte_fallas === 1) {
                        return `<span class="states status-pending">${data}</span>`;
                    } else if (row.id_estado_reporte_fallas === 2) {
                        return `<span class="states status-accepted">${data}</span>`;
                    } else if (row.id_estado_reporte_fallas === 3) {
                        return `<span class="states status-completed">${data}</span>`;
                    } else {
                        return data;
                    }
                }
            },
            {
                data: 'prioridad',
                title: 'Prioridad',
                render: function(data) {
                    if (data === "Baja" || data === "Low") {
                        return `<span class="states green-button">${data === "Low" ? "Baja" : data}</span>`;
                    } else if (data === "Media" || data === "Medium") {
                        return `<span class="states yellow-button">${data === "Medium" ? "Media" : data}</span>`;
                    } else if (data === "Alta" || data === "High") {
                        return `<span class="states red-button">${data === "High" ? "Alta" : data}</span>`;
                    } else {
                        return data;
                    }
                }
            },
            {
                data: 'tecnico_asignado_nombre',
                title: 'Técnico',
                render: function(data) {
                    return data ? data : '<span class="states dark-button">No asignado</span>';
                }
            }
        ],
        // Sin botones de exportar ni columna de acciones
    });

    // Delegación de eventos para ver detalles
    $('#myFaultReportTable').on('click', 'tbody tr', function() {
        var row = table.row(this).data();
        if (!row) return;
        // Abrir modal de detalles (idéntico al original)
        // Aquí puedes reutilizar el mismo AJAX de detalles del original
        // ...
    });
});
</script>
