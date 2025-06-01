<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;

use app\controllers\ActivityTypeController;

$activityTypeController = new ActivityTypeController();
$activityTypes = $activityTypeController->listTypes();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Reportes de Actividades</h3>
        <div class="table-actions">
            <button class="table-button open-modal" data-target-modal="activitiesReportModal" data-fetch="false">Crear Reporte</button>

        </div>
    </div>
    <table class="table row-border hover" id="activitiesReportTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Código</th>
                <th scope="col">Reportado Por</th>
                <th scope="col">Actividad</th>
                <th scope="col">Fecha de Actividad</th>
                <th scope="col">Participantes</th> <!-- NUEVA COLUMNA -->
                <th scope="col">Descripción</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
        </tbody>
    </table>
</div>
<div class="overlay-modal close-modal" data-modal-id="activitiesReportOverlay">
    <div class="modal-box" id="activitiesReportModal" data-modal-id="activitiesReportModal">
        <div class="modal-header">
            <h3 class="h3">Crear Reporte de Actividad</h3>
        </div>
        <form id="activitiesReportForm" action="index.php?view=activitiesReport&action=activitiesReport_create" method="POST" class="form" formType="activitiesReport" enctype="multipart/form-data">
            <input type="hidden" id="id_reporte_actividades" name="id_reporte_actividades" class="inputKey">
            <input type="hidden" id="existingImages" name="existingImages" value="">
            <div class="modal-body">
                <div class="form-row">
                    <div class="inputGroup flex-item">
                        <label for="fecha_actividad">Fecha de la Actividad</label>
                        <?php
                        $today = date('Y-m-d');
                        $weekAgo = date('Y-m-d', strtotime('-7 days'));
                        ?>
                        <input class="input" id="fecha_actividad" required type="date" name="fecha_actividad" min="<?= $weekAgo ?>" max="<?= $today ?>">
                    </div>
                    <div class="inputGroup flex-item">
                        <label for="id_tipo_actividad">Categoría</label>
                        <select class="input" id="id_tipo_actividad" required name="id_tipo_actividad">
                            <option value="">Seleccione</option>
                            <?php foreach ($activityTypes as $type) : ?>
                                <option value="<?php echo $type['id_tipo_actividad']; ?>"><?php echo $type['tipo_actividad']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="inputGroup full-width">
                    <label for="actividad">Titulo</label>
                    <input class="input" id="actividad" required type="text" name="titulo_reporte">
                </div>
                <div class="inputGroup full-width">
                    <label for="participantes-input">Participantes</label>
                    <div id="participantes-container" class="participantes-container">
                        <input type="text" id="participantes-input" class="input " placeholder="Buscar cédula o nombre..." autocomplete="off" maxlength="50">
                        <div id="participantes-suggestions" class="participantes-suggestions"></div>
                        <div id="participantes-list" class="participantes-list"></div>
                        <input type="hidden" id="participantes-hidden" name="participantes" value="">
                    </div>
                    <small id="participantes-error" style="color:red;display:none;">Debe agregar al menos un participante.</small>
                </div>
                <div class="inputGroup textArea">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" required name="contenido_reporte"></textarea>
                </div>
                <div id="dropzone-area" class="dropzone"></div>

            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button id="submitBtn" class="modal-button sentBtn" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="overlay-modal close-modal" data-modal-id="anotherModalOverlay">
    <div class="modal-box" id="anotherModal" data-modal-id="anotherModal">
        <div class="inputGroup textArea">
            <div class="modal-header">
                <h3 class="h3">Reporte de Actividad</h3>
            </div>
            <p style="max-width: 600px; text-align: justify;">
                <span>Descripción: </span>
                <br><br>
                <span id="contenido_reporte"></span>
            </p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let table = $('#activitiesReportTable').DataTable({
            ...commonDatatableConfig, // Utiliza el spread operator para incluir la configuración común
            layout: {
                topEnd: [
                    function() {
                        let container = document.createElement('div');
                        container.className = 'date-filter-container';

                        let divFechaInicio = document.createElement('div');
                        let labelInicio = document.createElement('label');
                        labelInicio.setAttribute('for', 'min-date');
                        labelInicio.textContent = 'Fecha Inicio: ';
                        let inputInicio = document.createElement('input');
                        inputInicio.type = 'text'; // Cambiado de 'date' a 'text'
                        inputInicio.id = 'min-date';
                        inputInicio.name = 'min-date';
                        inputInicio.className = 'date-filter';
                        inputInicio.readOnly = true; // Solo seleccionable desde el calendario
                        divFechaInicio.appendChild(labelInicio);
                        divFechaInicio.appendChild(inputInicio);
                        container.appendChild(divFechaInicio);

                        let divFechaFin = document.createElement('div');
                        let labelFin = document.createElement('label');
                        labelFin.setAttribute('for', 'max-date');
                        labelFin.textContent = 'Fecha Fin: ';
                        let inputFin = document.createElement('input');
                        inputFin.type = 'text'; // Cambiado de 'date' a 'text'
                        inputFin.id = 'max-date';
                        inputFin.name = 'max-date';
                        inputFin.className = 'date-filter';
                        inputFin.readOnly = true; // Solo seleccionable desde el calendario
                        divFechaFin.appendChild(labelFin);
                        divFechaFin.appendChild(inputFin);
                        container.appendChild(divFechaFin);

                        let resetButton = document.createElement('button');
                        resetButton.id = 'resetFilters';
                        resetButton.className = 'crud-button reset-button green-button';
                        resetButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                <path d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z" />
                                            </svg>`;
                        container.appendChild(resetButton);

                        return container;
                    }, 'search'
                ],
                topStart: "pageLength",
                bottomStart: 'buttons',
                bottomEnd: 'paging'
            },
            buttons: [{
                    extend: 'pdfHtml5', // Asegúrate de tener esta línea para usar la extensión PDF
                    text: 'Exportar a PDF', // O el texto que quieras para el botón
                    filename: 'Reporte_Fallas_' + new Date().toISOString().slice(0, 10), // Nombre del archivo
                    customize: function(doc) {

                        // Replace the string below with the actual base64 data of your image
                        const logoBase64 = '<?php echo $logoBase64; ?>';

                        doc.header = function(currentPage, pageCount, pageSize) {
                            return {
                                image: logoBase64,
                                width: 150, // Ajusta el ancho según necesites
                                alignment: 'center', // O 'left', 'right'
                                margin: [0, 20, 0, 0] // [left, top, right, bottom]
                            };
                        };



                        const columnasOcultar = [5, 6, 7]; // Índices de las columnas a ocultar (N° y Acciones en tu caso)

                        // Ocultar la cabecera de las columnas especificadas
                        columnasOcultar.forEach(index => {
                            if (doc.content[1].table.body[0] && doc.content[1].table.body[0][index]) {
                                doc.content[1].table.body[0][index].text = '';
                            }
                        });

                        // Ocultar las celdas de las columnas especificadas en el cuerpo
                        doc.content[1].table.body.forEach((row, rowIndex) => {
                            if (rowIndex > 0) { // No modificar la cabecera
                                columnasOcultar.forEach(index => {
                                    if (row && row[index]) {
                                        row[index].text = '';
                                    }
                                });
                            }
                        });

                        // Eliminar cualquier otro elemento que parezca un título genérico
                        const sabatesIndex = doc.content.findIndex(element => (
                            typeof element.text === 'string' && element.text.includes('SABATES') // Busca el texto específico
                        ));
                        if (sabatesIndex > -1) {
                            doc.content.splice(sabatesIndex, 1);
                        }
                        // *** AQUÍ VA TU CÓDIGO DE PERSONALIZACIÓN DEL PDF ***
                        // Por ejemplo, para añadir un título al inicio del PDF:
                        doc.content.splice(0, 0, {
                            text: 'Reporte de Actividades',
                            style: 'header'
                        });
                        doc.styles = {
                            header: {
                                fontSize: 18,
                                bold: true,
                                margin: [0, 40, 0, 0]
                            }
                        };
                        // *** FIN DE TU CÓDIGO DE PERSONALIZACIÓN ***

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
                    orientation: 'portrait', // O 'portrait' si lo prefieres
                    pageSize: 'A4', // O el tamaño de página que necesites
                    titleAttr: 'Exportar la tabla actual a PDF' // Atributo title para el botón (opcional)
                }
                // Puedes agregar más botones aquí (Excel, CSV, etc.) después de este objeto
            ],
            ajax: {
                url: 'index.php?view=activitiesReport&action=activitiesReport_fetch_page',
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
                {
                    data: 'codigo_reporte_actividades',
                },
                {
                    data: 'nombre_completo'
                },
                {
                    data: 'titulo_reporte'
                },
                {
                    data: 'fecha_actividad',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            const dateParts = data.split('-');
                            const date = new Date(dateParts[0], parseInt(dateParts[1]) - 1, dateParts[2]);
                            return date.toLocaleString('es-ES', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit'
                            });
                        }
                        return data;
                    }
                },
                {
                    data: 'participantes', // NUEVA COLUMNA
                    render: function(data, type, row) {
                        if (Array.isArray(data)) {
                            if (type === 'display') {
                                if (data.length === 1) {
                                    // Escapa comillas dobles para HTML
                                    const tooltip = data[0].replace(/"/g, '&quot;');
                                    return `<span class="participantes-tooltip" data-tippy-content="${tooltip}">${data[0]}</span>`;
                                } else if (data.length > 1) {
                                    // Mostrar el primero y "y X más"
                                    // Usa <br> para saltos de línea en el tooltip y escapa comillas dobles
                                    const tooltip = data.map(p => p.replace(/"/g, '&quot;')).join('<br>');
                                    return `<span class="participantes-tooltip" data-tippy-content="${tooltip}">${data[0]} <span style="color:#888;">y ${data.length - 1} más</span></span>`;
                                } else {
                                    return '';
                                }
                            }
                            // Para exportar (PDF/Excel), únelos por coma
                            return data.join(', ');
                        }
                        return data || '';
                    }
                },
                {
                    data: 'contenido_reporte',
                    render: function(data, type, row) {
                        if (type === 'display' && data != null && data.length > 50) {
                            return data.substring(0, 50) + '...';
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        // Botón de acciones tipo dropdown, igual que en faultReportTable-view.php
                        return `<div class="actions-dropdown-container">
                            <button class="crud-button actions-dropdown-btn dark-button">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>
                            </button>
                            <div class="actions-dropdown-menu" style="display:none; position:absolute; z-index:10;">
                                <button class="crud-button crud-option open-modal"
                                    data-target-modal="activitiesReportModal" data-fetch="true"
                                    data-id="${data.id_reporte_actividades}">
                                    Editar
                                </button>
                                <button class="crud-button crud-option open-modal"
                                    data-target-modal="anotherModal" data-fetch="true"
                                    onclick="displayReportContent('${row.titulo_reporte}','${row.contenido_reporte}')">
                                    Ver Detalles
                                </button>
                                <button onclick='sentDataActivities(this)' class="crud-button crud-option generate-report-btn"
                                    data-row='${encodeURIComponent(JSON.stringify(row))}'>
                                    Generar Reporte
                                </button>
                            </div>
                        </div>`;
                    }
                },
            ],
            initComplete: function() {
                // Inicializar los selectores DateTime después de que se han creado en el DOM
                minDate = new DateTime('#min-date', {
                    format: 'DD/MM/YYYY',
                    i18n: {
                        previous: 'Anterior',
                        next: 'Siguiente',
                        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        weekdays: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                        amPm: ['am', 'pm'],
                        today: 'Hoy',
                        clear: 'Limpiar',
                        close: 'Cerrar'
                    }
                });
                minDate.max(new Date()); // Forzar restricción después de inicializar

                maxDate = new DateTime('#max-date', {
                    format: 'DD/MM/YYYY',
                    i18n: {
                        previous: 'Anterior',
                        next: 'Siguiente',
                        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        weekdays: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                        amPm: ['am', 'pm'],
                        today: 'Hoy',
                        clear: 'Limpiar',
                        close: 'Cerrar'
                    }
                });
                maxDate.max(new Date()); // Forzar restricción después de inicializar

                // --- RESTRICCIÓN: max-date debe ser igual o mayor que min-date ---
                $('#min-date').on('change', function() {
                    const minVal = minDate.val();
                    if (minVal) {
                        maxDate.min(minVal); // Establece el mínimo de max-date
                        // Si la fecha fin es menor que la nueva fecha inicio, la limpia
                        if (maxDate.val() && maxDate.val() < minVal) {
                            maxDate.val(minVal);
                        }
                    } else {
                        maxDate.min(null); // Sin restricción si no hay min
                    }
                });

                let api = this.api();

                // Crear la función de filtro personalizada (se mantiene igual)
                // Reemplaza la función de filtro personalizada con esta versión corregida
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        let minDateObj = minDate.val();
                        let maxDateObj = maxDate.val();
                        let dateStr = data[4]; // La fecha en formato YYYY-MM-DD

                        if ((minDateObj === null && maxDateObj === null) || dateStr === null || dateStr === undefined) {
                            return true;
                        }

                        // Función para normalizar fechas considerando la zona horaria
                        function normalize(date, isEndOfDay = false) {
                            if (!date) return null;

                            // Si es un objeto Date
                            if (Object.prototype.toString.call(date) === '[object Date]') {
                                // Ajustar para zona horaria local
                                const offset = date.getTimezoneOffset() * 60000;
                                const localDate = new Date(date.getTime() - offset);

                                if (isEndOfDay) {
                                    // Para fecha fin, establecer a las 23:59:59 del día seleccionado
                                    return new Date(localDate.setHours(23, 59, 59, 999)).getTime();
                                }
                                // Para fecha inicio, establecer a las 00:00:00 del día seleccionado
                                return new Date(localDate.setHours(0, 0, 0, 0)).getTime();
                            }

                            // Si es string en formato YYYY-MM-DD
                            if (typeof date === 'string') {
                                const match = date.match(/(\d{4})-(\d{2})-(\d{2})/);
                                if (match) {
                                    const year = parseInt(match[1]);
                                    const month = parseInt(match[2]) - 1;
                                    const day = parseInt(match[3]);
                                    const localDate = new Date(year, month, day);
                                    return normalize(localDate, isEndOfDay);
                                }
                            }

                            return null;
                        }

                        // Convertir la fecha del registro
                        const dateParts = dateStr.split('-');
                        const dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, dateParts[2]);
                        const dateTime = normalize(dateObj);

                        // Convertir fechas de filtro
                        const minTime = minDateObj ? normalize(minDateObj) : null;
                        const maxTime = maxDateObj ? normalize(maxDateObj, true) : null; // true para indicar que es fecha fin

                        console.log('Filtro fechas ajustado:', {
                            registro: dateObj,
                            registroMs: dateTime,
                            minDateObj,
                            minTime,
                            maxDateObj,
                            maxTime
                        });

                        if (minTime !== null && maxTime !== null) {
                            return dateTime >= minTime && dateTime <= maxTime;
                        } else if (minTime !== null) {
                            return dateTime >= minTime;
                        } else if (maxTime !== null) {
                            return dateTime <= maxTime;
                        }
                        return true;
                    }
                );

                // Redibujar la tabla cuando cambien las fechas (se mantiene igual)
                $('#min-date, #max-date').on('change', function() {
                    table.draw();
                });

                // Evento para resetear los filtros (ahora aplicado al botón dentro del layout)
                $('#resetFilters').on('click', function() {
                    $('#min-date, #max-date').val(''); // Limpiar los campos de entrada manualmente
                    minDate.val(null); // Resetear campo de fecha mínima en DateTime
                    maxDate.val(null); // Resetear campo de fecha máxima en DateTime
                    table.draw(); // Redibujar la tabla
                });
            },
            drawCallback: function() {
                // Destruye tooltips anteriores para evitar duplicados
                if (window.tippyInstances) {
                    window.tippyInstances.forEach(instance => instance.destroy());
                }
                // Inicializa Tippy en los elementos actuales
                window.tippyInstances = tippy('.participantes-tooltip', {
                    allowHTML: true,
                    placement: 'top',
                    theme: 'light-border',
                    interactive: false,
                });
            }
        });

        function displayReportContent(titulo, reportText) {
            const modal = document.getElementById('anotherModal');
            const title = modal.querySelector('.modal-header h3');
            const text = modal.querySelector('#contenido_reporte');
            if (text) {
                text.textContent = reportText;
            }
            if (title) {
                title.textContent = titulo;
            }
        }

        // Mostrar toda la información de la fila en la modal de detalles
        function displayFullReportContent(row) {
            if (!row) return;
            const modal = document.getElementById('anotherModal');
            const container = modal.querySelector('.inputGroup.textArea');
            let participantes = '';
            if (Array.isArray(row.participantes)) {
                participantes = row.participantes.join(', ');
            } else if (typeof row.participantes === 'string') {
                participantes = row.participantes;
            }
            // Formatear fecha
            let fecha = row.fecha_actividad;
            if (fecha && /^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
                const dateParts = fecha.split('-');
                fecha = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
            }
            container.innerHTML = `
                <div class="details-modal-container">
                    <div class="h3">Reporte de Actividad</div>
                    <div class="details-code"><span class="details-code-label">Código:</span> ${row.codigo_reporte_actividades || ''}</div>
                    <table class="details-table">
                        <tr><th class="details-label">Reportado Por</th><td class="details-value">${row.nombre_completo || ''}</td></tr>
                        <tr><th class="details-label">Actividad</th><td class="details-value">${row.titulo_reporte || ''}</td></tr>
                        <tr><th class="details-label">Fecha de Actividad</th><td class="details-value">${fecha || ''}</td></tr>
                        <tr><th class="details-label">Participantes</th><td class="details-value details-participantes-cell">${participantes || ''}</td></tr>
                    </table>
                    <div class="details-description-title">Descripción</div>
                    <div class="details-description-modal">${row.contenido_reporte || ''}</div>
                    <div class="details-footer-modal">
                        <button class="modal-button close-modal" type="button">Cerrar</button>
                    </div>
                </div>
            `;
        }

        // Al abrir la modal de detalles, mostrar toda la información
        $(document).on('click', '.open-modal[data-target-modal="anotherModal"]', function() {
            const row = $(this).closest('tr').length ? $('#activitiesReportTable').DataTable().row($(this).closest('tr')).data() : null;
            if (row) {
                displayFullReportContent(row);
            } else {
                // Si no se encuentra la fila, limpiar la modal
                displayFullReportContent({});
            }
        });

        // Vaciar la información de la modal al abrirla
        $(document).on('click', '.open-modal[data-target-modal="anotherModal"]', function() {
            const modal = document.getElementById('anotherModal');
            if (modal) {
                const title = modal.querySelector('.modal-header h3');
                const text = modal.querySelector('#contenido_reporte');
                if (title) title.textContent = '';
                if (text) text.textContent = '';
            }
        });

        // Al abrir modal para editar, deshabilitar fecha y quitar name para que no se envíe
        $(document).on('click', '.open-modal[data-target-modal="activitiesReportModal"][data-fetch="true"]', function() {
            setTimeout(function() {
                var fechaInput = document.getElementById('fecha_actividad');
                if (fechaInput) {
                    fechaInput.disabled = true;
                    fechaInput.removeAttribute('name');
                }
            }, 100);
        });
        // Al abrir modal para crear, habilitar fecha y restaurar name
        $(document).on('click', '.open-modal[data-target-modal="activitiesReportModal"][data-fetch="false"]', function() {
            setTimeout(function() {
                var fechaInput = document.getElementById('fecha_actividad');
                if (fechaInput) {
                    fechaInput.disabled = false;
                    fechaInput.setAttribute('name', 'fecha_actividad');
                }
            }, 100);
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Validación de fecha manual para el input de fecha_actividad
        const fechaInput = document.getElementById('fecha_actividad');
        if (fechaInput) {
            fechaInput.addEventListener('input', function() {
                // Solo validar si la fecha tiene el formato completo (YYYY-MM-DD)
                if (fechaInput.value && /^\d{4}-\d{2}-\d{2}$/.test(fechaInput.value)) {
                    const min = new Date(fechaInput.min);
                    const max = new Date(fechaInput.max);
                    const value = new Date(fechaInput.value);
                    if (value < min || value > max) {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Fecha inválida',
                                text: 'La fecha debe estar entre hace 7 días y hoy.',
                                confirmButtonText: 'Aceptar',
                                customClass: {
                                    popup: 'swal2-popup'
                                }
                            }).then(() => {
                                fechaInput.value = '';
                                fechaInput.setCustomValidity('');
                            });
                        } else {
                            alert('La fecha debe estar entre hace 7 días y hoy.');
                            fechaInput.value = '';
                            fechaInput.setCustomValidity('');
                        }
                    } else {
                        fechaInput.setCustomValidity('');
                    }
                } else {
                    fechaInput.setCustomValidity('');
                }
            });
        }
    });

    // --- PARTICIPANTES AUTOCOMPLETE ---
    window.participantes = [];
    const input = document.getElementById('participantes-input');
    const suggestions = document.getElementById('participantes-suggestions');
    const list = document.getElementById('participantes-list');
    const hidden = document.getElementById('participantes-hidden');
    const error = document.getElementById('participantes-error');

    input.addEventListener('input', function() {
        const query = input.value.trim();
        if (query.length < 2) {
            suggestions.innerHTML = '';
            return;
        }
        // AJAX para buscar participantes
        fetch('index.php?view=activitiesReport&action=searchParticipants&q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                suggestions.innerHTML = '';
                if (Array.isArray(data)) {
                    data.forEach(persona => {
                        // Evitar duplicados
                        if (window.participantes.some(p => p.cedula === persona.cedula)) return;
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = persona.cedula + ' - ' + persona.nombre;
                        div.addEventListener('click', function() {
                            agregarParticipante(persona);
                            suggestions.innerHTML = '';
                            input.value = '';
                        });
                        suggestions.appendChild(div);
                    });
                }
            });
    });

    function agregarParticipante(persona) {
        if (window.participantes.length >= 4) return; // No permitir más de 4
        if (window.participantes.some(p => p.cedula === persona.cedula)) return;
        window.participantes.push(persona);
        renderParticipantes();
    }

    function eliminarParticipante(cedula) {
        window.participantes = window.participantes.filter(p => p.cedula !== cedula);
        renderParticipantes();
    }

    function renderParticipantes() {
        list.innerHTML = '';
        window.participantes.forEach(persona => {
            const span = document.createElement('span');
            span.className = 'participante-span';
            span.textContent = persona.cedula + ' - ' + persona.nombre;
            const x = document.createElement('span');
            x.className = 'participante-remove';
            x.textContent = ' ×';
            x.style.cursor = 'pointer';
            x.addEventListener('click', function() {
                eliminarParticipante(persona.cedula);
            });
            span.appendChild(x);
            list.appendChild(span);
        });
        // Actualizar campo oculto
        hidden.value = window.participantes.map(p => p.cedula).join(',');
        // Validar mínimo 1 participante
        if (window.participantes.length === 0) {
            error.style.display = '';
        } else {
            error.style.display = 'none';
        }
        // Desactivar input si hay 4 participantes
        if (window.participantes.length >= 4) {
            input.disabled = true;
            suggestions.innerHTML = '';
        } else {
            input.disabled = false;
        }
    }

    document.getElementById('activitiesReportForm').addEventListener('submit', function(e) {
        if (window.participantes.length === 0) {
            error.style.display = '';
            e.preventDefault();
            input.focus();
        }
    });
</script>
<script>
    // Dropdown de acciones (igual que en faultReportTable-view.php)
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('activitiesReportTable').addEventListener('click', function(e) {
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

    // Envío de datos para generar el reporte PDF
    function sentDataActivities(btn) {
        const row = JSON.parse(decodeURIComponent(btn.getAttribute('data-row')));
        // Puedes hacer un fetch si necesitas datos extra, o enviar el row directamente
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?view=activitiesReportV';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'row';
        input.value = JSON.stringify(row);

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>
<style>
    .participantes-container {
        position: relative;
    }

    .participantes-suggestions {
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        z-index: 10;
        width: 100%;
        max-height: 150px;
        overflow-y: auto;
    }

    .suggestion-item {
        padding: 5px 10px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background: #f0f0f0;
    }

    .participantes-list {
        margin-top: 5px;
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        max-width: 600px;
        gap: 4px;
    }

    .participante-span {
        display: inline-flex;
        align-items: center;
        background: #e0e0e0;
        border-radius: 12px;
        padding: 3px 10px;
        margin: 2px 0;
        font-size: 0.95em;
        white-space: nowrap;
    }

    .participante-remove {
        color: #c00;
        margin-left: 6px;
        font-weight: bold;
    }

    .participantes-tooltip {
        cursor: pointer;
        border-bottom: 1px dotted #888;
        white-space: pre-line;
    }

    .details-modal-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }
    .details-title {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 8px;
        text-align: center;
        width: 100%;
    }
    .details-code {
        font-weight: bold;
        font-size: 1.1em;
        margin: 1rem 0;
        text-align: center;
        width: 100%;
    }
    .details-code-label {
        font-weight: normal;
    }
    .details-table {
        width: 100%;
        max-width: 420px;
        margin: 0 auto 12px auto;
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
        max-width: 420px;
        margin-left: auto;
        margin-right: auto;
        box-sizing: border-box;
    }
    .details-description-modal {
        width: 100%;
        max-width: 420px;
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
        white-space: pre-line;
        margin-top: 4px;
    }
    .details-participantes-cell {
        line-height: 2.1;
    }
    .details-footer-modal {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 10px;
        margin-bottom: 2px;
    }
</style>