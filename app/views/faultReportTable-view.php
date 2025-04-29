<?php

use app\controllers\FaultTypeController;

$faultTypeController = new FaultTypeController();
$faultTypes = $faultTypeController->listFaultTypes();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Reportes de Fallas</h3>
        <div class="table-actions">
            <button class="table-button open-modal" data-modal="faultReportModal" data-fetch="false">Crear Reporte</button>
        </div>
    </div>
    <table class="table row-border hover" id="faultReportTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">ID Reporte</th>
                <th scope="col">Reportado Por</th>
                <th scope="col">Tipo de Falla</th>
                <th scope="col">ID Equipo</th>
                <th scope="col">Fecha del Reporte</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de la Falla</th>
                <th scope="col">Reparacion Asignada a</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
        </tbody>
    </table>
</div>
<div class="overlay-modal">
    <div class="modal-box" id="faultReportModal">
        <div class="modal-header">
            <h3 class="h3">Crear Reporte de Falla</h3>
        </div>
        <form action="index.php?view=faultReport&action=faultReport_fetch_create" method="POST" class="form" formType="faultReport">
            <div class="modal-body">
                <div class="userDetails">
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
        const userDetailsContainer = document.querySelector('.userDetails');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        const faultReportModal = document.getElementById('faultReportModal');

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

            if (cedulaInput) {
                cedulaInput.parentElement.remove();
            }
            if (idPCInput) {
                idPCInput.parentElement.remove();
            }
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
            } else {
                destroyFields(); // Eliminar los campos dinámicamente
            }
        });

        // Escuchar el evento de cerrar el modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', resetModal);
        });

        // También restablecer el modal al abrirlo
        faultReportModal.addEventListener('show', resetModal);
    });
</script>
<script>
    // Pasar el valor de $_SESSION['username'] al cliente
    const currentUsername = "<?php echo $_SESSION['username']; ?>";
</script>
<script>
    $(document).ready(function() {
        $('#faultReportTable').DataTable({
            ...commonDatatableConfig, // Configuración común
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
                    data: 'id_reporte_fallas'
                },
                {
                    data: 'usuario_reportante'
                },
                {
                    data: 'tipo_falla'
                },
                {
                    data: 'id_equipo_informatico',
                    render: function(data, type, row) {
                        // 'data' es el valor de la celda para esta columna y fila
                        if (data === null || data === undefined) {
                            return 'No Aplica'; // O cualquier otro texto/HTML que quieras mostrar
                        } else {
                            return data; // Muestra el valor original si no es null
                        }
                    }
                },
                {
                    data: 'fecha_hora_reporte_fallas'
                },
                {
                    data: 'estado_reporte_fallas'
                },
                {
                    data: 'fecha_falla'
                },
                {
                    data: 'tecnico_asignado',
                    render: function(data, type, row) {
                        // 'data' es el valor de la celda para esta columna y fila
                        if (data === null || data === undefined) {
                            return `<button class="table-button edit-button aceptar-reparacion-button" 
                                        data-report-id="${row.id_reporte_fallas}" 
                                        onclick="acceptRepair(${row.id_reporte_fallas})">
                                        Aceptar Reparación
                                    </button>`;
                        } else if (data == currentUsername) {
                            return data;
                        }
                        else {
                            return data; // Muestra el valor original si no es null
                        }
                    }
                }

            ]
        });
    });
</script>
<script>
    const inputFecha = document.getElementById('fecha_falla');

    // Obtener la fecha actual
    const fechaActual = new Date();

    // Calcular la fecha de un mes atrás
    const mesAnterior = new Date();
    mesAnterior.setMonth(fechaActual.getMonth() - 1);

    // Establecer los límites de `min` y `max`
    inputFecha.min = mesAnterior.toISOString().split('T')[0]; // Inicio: Hace un mes
    inputFecha.max = fechaActual.toISOString().split('T')[0]; // Fin: Hoy
</script>