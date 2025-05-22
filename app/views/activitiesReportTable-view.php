<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;
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
                        <input class="input" id="fecha_actividad" required type="date" name="fecha_actividad">
                    </div>
                    <div class="inputGroup flex-item">
                        <label for="actividad">Titulo</label>
                        <input class="input" id="actividad" required type="text" name="titulo_reporte">
                    </div>
                </div>
                <div class="inputGroup full-width">
                    <label for="actividad">Titulo</label>
                    <input class="input" id="actividad" required type="text" name="titulo_reporte">
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
                        inputInicio.type = 'text';
                        inputInicio.id = 'min-date';
                        inputInicio.name = 'min-date';
                        inputInicio.className = 'date-filter';
                        divFechaInicio.appendChild(labelInicio);
                        divFechaInicio.appendChild(inputInicio);
                        container.appendChild(divFechaInicio);

                        let divFechaFin = document.createElement('div');
                        let labelFin = document.createElement('label');
                        labelFin.setAttribute('for', 'max-date');
                        labelFin.textContent = 'Fecha Fin: ';
                        let inputFin = document.createElement('input');
                        inputFin.type = 'text';
                        inputFin.id = 'max-date';
                        inputFin.name = 'max-date';
                        inputFin.className = 'date-filter';
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



                        const columnasOcultar = [5, 6]; // Índices de las columnas a ocultar (N° y Acciones en tu caso)

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
            columns: [{
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
                        return `<div class="button-container">
                                <button class="crud-button green-button open-modal" data-target-modal="activitiesReportModal" data-fetch="true" data-id="${data.id_reporte_actividades}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                                </button>
                                <button class="crud-button yellow-button open-modal " data-target-modal="anotherModal" data-fetch="true" onclick="displayReportContent('${row.titulo_reporte}','${row.contenido_reporte}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-134 0-244.5-72T61-462q-5-9-7.5-18.5T51-500q0-10 2.5-19.5T61-538q64-118 174.5-190T480-800q134 0 244.5 72T899-538q5 9 7.5 18.5T909-500q0 10-2.5 19.5T899-462q-64 118-174.5 190T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5-160.5T480-280Z"/></svg>
                                </button>
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


                let api = this.api();

                // Crear la función de filtro personalizada (se mantiene igual)
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        let minDateObj = minDate.val();
                        let maxDateObj = maxDate.val();
                        let dateStr = data[4]; // La fecha sigue estando en formato YYYY-MM-DD

                        if ((minDateObj === null && maxDateObj === null) || dateStr === null || dateStr === undefined) {
                            return true;
                        }

                        let dateObj;
                        try {
                            const dateParts = dateStr.split('-');
                            dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                        } catch (error) {
                            console.error("Error al parsear la fecha:", dateStr, error);
                            return false;
                        }

                        if (minDateObj !== null && maxDateObj !== null) {
                            return dateObj >= minDateObj && dateObj <= maxDateObj;
                        } else if (minDateObj !== null) {
                            return dateObj >= minDateObj;
                        } else if (maxDateObj !== null) {
                            return dateObj <= maxDateObj;
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
    });
</script>