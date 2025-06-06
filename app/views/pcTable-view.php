<?php

use app\controllers\StatePcController;

$statePcController = new StatePcController();
$statePcController = $statePcController->listStates();
?>
<style>
    #pcModal {
        max-height: 90vh;
    }

    #ram-modules,
    #storage-modules {
        max-height: 300px;
        /* Ajusta según tu diseño */
        overflow-y: auto;
        margin-bottom: 1em;
    }
</style>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Equipos Informáticos</h3>
        <div class="table-actions">
            <a href="index.php?view=pcDeincorporatedTable">
                <button type="button" class="table-button dark-button">Ver Desincorporados</button>
            </a>
            <button class="table-button open-modal" data-target-modal="pcModal" data-fetch="false">Añadir Equipo</button>
        </div>
    </div>
    <table class="table row-border hover" id="pcTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Código</th>
                <th scope="col">Fabricante</th>
                <th scope="col">Estado</th>
                <th scope="col">Asignado a</th>
                <th scope="col">Procesador</th>
                <th scope="col">Motherboard</th>
                <th scope="col">Fuente</th>
                <!-- <th scope="col">RAM</th>
                <th scope="col">Almacenamiento</th> -->
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
            <!-- Los datos se cargarán dinámicamente -->
        </tbody>
    </table>
</div>
<div class="overlay-modal" id="pcModalOverlay" data-modal-id="pcModalOverlay">
    <div class="modal-box" id="pcModal" data-modal-id="pcModal">
        <div class="modal-header">
            <h3 class="h3">Registrar un equipo</h3>
        </div>
        <div class="modal-body">
            <div class="progressBar">
                <div class="bullet bulletActive">
                    <span class="step-number display">1</span>
                    <div class="check"><img src="img/check.svg" alt=""></div>
                </div>
                <div class="bullet">
                    <span class="step-number display">2</span>
                    <div class="check"><img src="img/check.svg" alt=""></div>
                </div>
                <div class="bullet">
                    <span class="step-number display">3</span>
                    <div class="check"><img src="img/check.svg" alt=""></div>
                </div>
                <div class="bullet">
                    <span class="step-number display">4</span>
                    <div class="check"><img src="img/check.svg" alt=""></div>
                </div>
                <div class="bullet">
                    <span class="step-number display">5</span>
                    <div class="check"><img src="img/check.svg" alt=""></div>
                </div>
                <div class="bullet">
                    <span class="step-number display">6</span>
                    <div class="check"><img src="img/check.svg" alt=""></div>
                </div>
            </div>
            <div class="form-layout">
                <form action="index.php?view=pc&action=pc_fetch_create" method="POST" id="pcForm" class="multi-step-form form" formType="pc">
                    <input type="hidden" id="id_equipo_informatico" name="id_equipo_informatico" class="inputKey">

                    <fieldset class="page-form slidePage">
                        <legend>
                            <div class="pcHeader">
                                <h4 class="h4">Información General</h4>
                            </div>
                        </legend>
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante">Fabricante del Equipo:</label>
                                <input type="text" id="fabricante" name="fabricante_equipo_informatico" class="input capitalize-first only-letters no-empty-after-space" required maxlength="35">
                            </div>
                            <div class="inputGroup">
                                <label for="estado">Estado del Equipo:</label>
                                <select id="estado" name="id_estado_equipo" class="input" required>
                                    <option value="" selected>Seleccione</option>
                                    <?php foreach ($statePcController as $estado): ?>
                                        <option value="<?= htmlspecialchars($estado['id_estado_equipo_informatico']) ?>">
                                            <?= htmlspecialchars($estado['estado_equipo_informatico']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <div class="pcHeader">
                                <h4 class="h4">Procesador</h4>
                            </div>
                        </legend>
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante_procesador">Fabricante del procesador:</label>
                                <input type="text" id="fabricante_procesador" name="fabricante_procesador" class="input capitalize-first only-letters no-spaces" required>
                            </div>
                            <div class="inputGroup">
                                <label for="nombre_procesador">Nombre del procesador:</label>
                                <input type="text" id="nombre_procesador" name="nombre_procesador" class="input no-empty-after-space" required>
                            </div>
                            <div class="inputGroup">
                                <label for="nucleos">Núcleos:</label>
                                <input type="text" id="nucleos" name="nucleos" class="input numbers" required maxlength="2">
                            </div>
                            <div class="inputGroup">
                                <label for="frecuencia_procesador">Frecuencia del procesador (GHz):</label>
                                <input type="text" id="frecuencia_procesador" name="frecuencia_procesador" class="input auto-decimal" required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <div class="pcHeader">
                                <h4 class="h4">Motherboard</h4>
                            </div>
                        </legend>
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante_motherboard">Fabricante de la motherboard:</label>
                                <input type="text" id="fabricante_motherboard" name="fabricante_motherboard" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="modelo_motherboard">Modelo de la motherboard:</label>
                                <input type="text" id="modelo_motherboard" name="modelo_motherboard" class="input" required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <div class="pcHeader">
                                <h4 class="h4">Fuente</h4>
                            </div>
                        </legend>
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante_fuente">Fabricante de la fuente:</label>
                                <input type="text" id="fabricante_fuente" name="fabricante_fuente_poder" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="wattage_fuente">Wattage de la fuente:</label>
                                <input type="text" id="wattage_fuente" name="wattage_fuente" class="input" required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <div class="pcHeader">
                                <h4 class="h4">RAM</h4>
                            </div>
                        </legend>
                        <div class="inputGroup">
                            <label for="tipo_ram">Tipo de RAM:</label>
                            <input type="text" id="tipo_ram" name="tipo_ram" class="input" required>
                        </div>
                        <div id="ram-modules">
                            <div class="ram-module">
                                <h5 class="ram-title">Módulo 1</h5>
                                <div class="form-row">
                                    <div class="inputGroup">
                                        <label>Fabricante de la RAM:</label>
                                        <input type="text" name="fabricante_ram[]" class="input" required>
                                    </div>
                                    <div class="inputGroup">
                                        <label>Frecuencia de la RAM (MHz):</label>
                                        <input type="text" name="frecuencia_ram[]" class="input" required>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="inputGroup">
                                        <label>Capacidad de la RAM (GB):</label>
                                        <input type="text" name="capacidad_ram[]" class="input" required>
                                    </div>

                                    <button type="button" class="remove-ram btn-mini">Eliminar Módulo</button>
                                </div>

                            </div>
                        </div>
                        <div class="addModuleContainer">
                            <button type="button" id="add-ram" class="btn-mini" title="Agregar Modulo RAM">
                                <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px" fill="#515151">
                                    <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                                </svg>
                            </button>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <div class="pcHeader">
                                <h4 class="h4">Almacenamiento</h4>
                            </div>
                        </legend>
                        <div id="storage-modules">
                            <div class="storage-module">
                                <h5 class="storage-title">Módulo 1</h5>
                                <div class="form-row">

                                    <div class="inputGroup">
                                        <label for="fabricante_almacenamiento">Fabricante del almacenamiento:</label>
                                        <input type="text" id="fabricante_almacenamiento" name="fabricante_almacenamiento[]" class="input" required>
                                    </div>
                                    <div class="inputGroup">
                                        <label for="tipo_almacenamiento">Tipo de almacenamiento:</label>
                                        <input type="text" id="tipo_almacenamiento" name="tipo_almacenamiento[]" class="input" required>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="inputGroup">
                                        <label for="capacidad_almacenamiento">Capacidad del almacenamiento (GB):</label>
                                        <input type="text" id="capacidad_almacenamiento" name="capacidad_almacenamiento[]" class="input" required>
                                    </div>
                                    <button type="button" class="remove-storage btn-mini">Eliminar Módulo</button>
                                </div>
                            </div>
                        </div>
                        <div class="addModuleContainer">
                            <button type="button" id="add-storage" class="btn-mini" title="Agregar Módulo de Almacenamiento">
                                <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px" fill="#515151">
                                    <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                                </svg>
                            </button>
                        </div>
                    </fieldset>

                </form>
            </div>
        </div>
        <div class="modal-footer btnArea">
            <button class="modal-button prevBtn" type="button">Cancelar</button>
            <button class="modal-button sentBtn" type="button">Siguiente</button>
        </div>
    </div>
</div>
<!-- Modal Asignar Equipo -->
<div class="overlay-modal" id="assignPcModalOverlay" data-modal-id="assignPcModalOverlay" style="display:none;">
    <div class="modal-box" id="assignPcModal" data-modal-id="assignPcModal">
        <div class="modal-header">
            <h3 class="h3">Asignar equipo</h3>
        </div>
        <form class="form" formType="pc" method="POST" action="index.php?view=pc&action=assign_pc">
            <div class="modal-body">
                <input type="hidden" id="id_equipo_informatico" name="id_equipo_informatico">
                <div class="inputGroup">
                    <label for="assign_cedula">Cédula de la persona a asignar:</label>
                    <input type="text" id="assign_cedula" name="cedula" class="input ci" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Asignar</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Reasignar Equipo -->
<div class="overlay-modal" id="reassignPcModalOverlay" data-modal-id="reassignPcModalOverlay" style="display:none;">
    <div class="modal-box" id="reassignPcModal" data-modal-id="reassignPcModal">
        <div class="modal-header">
            <h3 class="h3">Reasignar equipo</h3>
        </div>
        <form class="form" formType="pc" method="POST" action="index.php?view=pc&action=assign_pc">
            <div class="modal-body">
                <input type="hidden" id="id_equipo_informatico" name="id_equipo_informatico">
                <div class="inputGroup">
                    <label for="reassign_cedula">Cédula de la persona:</label>
                    <input type="text" id="reassign_cedula" name="cedula" class="input ci" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Reasignar</button>
            </div>
        </form>
    </div>
</div>
<script>
    window.userIsAdmin = <?php echo (isset($_SESSION['role']) && $_SESSION['role'] == 1) ? 'true' : 'false'; ?>;

    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var estado = urlParams.get('estado');
        $('#pcTable').DataTable({
            ...commonDatatableConfig, // Configuración común
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                filename: 'Reporte_Equipos_' + new Date().toISOString().slice(0, 10),
                customize: function(doc) {
                    // Logo igual que en faultReportTable
                    const logoBase64 = '<?php
                                        $imagenPath = "./img/banner_SABATES.png";
                                        if (file_exists($imagenPath)) {
                                            $imagenData = base64_encode(file_get_contents($imagenPath));
                                            echo 'data:image/png;base64,' . $imagenData;
                                        } else {
                                            echo '';
                                        }
                                        ?>';
                    doc.header = function() {
                        return {
                            image: logoBase64,
                            width: 150,
                            alignment: 'center',
                            margin: [0, 20, 0, 0]
                        };
                    };

                    // Ocultar columnas no deseadas
                    const columnasOcultar = [9];
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

                    // Eliminar cualquier otro elemento que parezca un título genérico
                    const sabatesIndex = doc.content.findIndex(element => (
                        typeof element.text === 'string' && element.text.includes('SABATES')
                    ));
                    if (sabatesIndex > -1) {
                        doc.content.splice(sabatesIndex, 1);
                    }

                    // Título personalizado
                    doc.content.splice(0, 0, {
                        text: 'Reporte de Equipos Informáticos',
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
                url: 'index.php?view=pc&action=pc_fetch_page',
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
                    data: 'codigo_equipo'
                },
                {
                    data: 'fabricante_equipo_informatico'
                },
                {
                    data: 'estado_equipo_informatico',
                    render: function(data, type, row) {
                        if (data === 'Desincorporado') {
                            return `<span class="states dark-button">${data}</span>`;
                        } else if (data === 'En reparación') {
                            return `<span class="states yellow-button">${data}</span>`;
                        } else if (data === 'Operativo') {
                            return `<span class="states green-button">${data}</span>`;
                        } else if (data === 'Averiado') {
                            return `<span class="states red-button">${data}</span>`;
                        }
                    }
                },
                {
                    data: 'nombre_completo',
                    render: function(data, type, row) {
                        if (!data || data === '' || data === null) {
                            return '<span class="states dark-button">Sin asignar</span>';
                        }
                        return data;
                    }
                },
                {
                    data: 'nombre_procesador'
                },
                {
                    data: 'motherboard'
                },
                {
                    data: 'fuente'
                },
                // {
                //     data: 'capacidad_ram_total'
                // },
                // {
                //     data: 'almacenamiento_total'
                // },
                {
                    data: null,
                    render: function(data, type, row) {
                        let actions = `<div class="actions-dropdown-container">
                            <button class="crud-button actions-dropdown-btn dark-button">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>
                            </button>
                            <div class="actions-dropdown-menu" style="display:none; position:absolute; z-index:10;">
                                <button class="crud-button crud-option open-modal" data-target-modal="pcModal" data-fetch="true" data-id="${data.id_equipo_informatico}">Editar</button>`;
                        // Botón Asignar/Reasignar
                        if (!row.nombre_completo || row.nombre_completo === '' || row.nombre_completo === null) {
                            actions += `<button class="crud-button crud-option assign-pc-btn open-modal" data-fetch="true" data-target-modal="assignPcModal" data-id="${data.id_equipo_informatico}">Asignar equipo</button>`;
                        } else {
                            actions += `<button class="crud-button crud-option reassign-pc-btn open-modal" data-target-modal="reassignPcModal" data-id="${data.id_equipo_informatico}">Reasignar equipo</button>`;
                            actions += `<button class="crud-button crud-option unassign-pc-btn" data-id="${data.id_equipo_informatico}">Desasignar equipo</button>`;
                        }
                        // Botón Desincorporar (solo admin y si NO está desincorporado)
                        if (window.userIsAdmin && row.estado_equipo_informatico !== 'Desincorporado') {
                            actions += `<button class="crud-button crud-option deincorporate-pc-btn" data-id="${data.id_equipo_informatico}">Desincorporar equipo</button>`;
                        }
                        // Botón Reincorporar (solo admin y si SÍ está desincorporado)
                        if (window.userIsAdmin && row.estado_equipo_informatico === 'Desincorporado') {
                            actions += `<button class="crud-button crud-option reincorporate-pc-btn" data-id="${data.id_equipo_informatico}">Reincorporar equipo</button>`;
                        }
                        // Botón Generar Reporte
                        actions += `<button class="crud-button crud-option generate-pc-report-btn" data-row='${JSON.stringify(data)}'>Generar Reporte</button>`;
                        actions += `</div></div>`;
                        return actions;
                    }
                }
            ],
            initComplete: function() {
                // Aplicar el filtro si el parámetro 'estado' existe
                if (estado) {
                    this.api().search(estado).draw();
                }
            }
        });
    });

    // Dropdown de acciones para pcTable (igual que en otras tablas)
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pcTable').addEventListener('click', function(e) {
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

    // Script para setear el id_equipo_informatico en las modales de asignar y reasignar
    $(document).on('click', '.assign-pc-btn.open-modal', function() {
        const id = $(this).data('id');
        $('#assignPcModal input[name="id_equipo_informatico"]').val(id);
    });
    $(document).on('click', '.reassign-pc-btn.open-modal', function() {
        const id = $(this).data('id');
        $('#reassignPcModal input[name="id_equipo_informatico"]').val(id);
    });

    // Acción única de desincorporar equipo (solo admin)
    $(document).on('click', '.deincorporate-pc-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        if (window.Swal) {
            Swal.fire({
                title: '¿Está seguro?',
                text: '¿Desea desincorporar este equipo? Esta acción cambiará su estado a "Desincorporado".',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desincorporar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'swal2-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('index.php?view=pc&action=deincorporate_pc', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id_equipo_informatico=${encodeURIComponent(id)}`
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Desincorporado', data.message, 'success');
                                $('#pcTable').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo desincorporar.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                        });
                }
            });
        }
    });

    // Acción para reincorporar equipo (solo admin)
    $(document).on('click', '.reincorporate-pc-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        if (window.Swal) {
            Swal.fire({
                title: '¿Está seguro?',
                text: '¿Desea reincorporar este equipo? Esta acción cambiará su estado a "Operativo".',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, reincorporar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'swal2-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('index.php?view=pc&action=reincorporate_pc', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id_equipo_informatico=${encodeURIComponent(id)}`
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Reincorporado', data.message, 'success');
                                $('#pcTable').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo reincorporar.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                        });
                }
            });
        }
    });

    // Solo deja este script para el botón "Generar Reporte" (no lo dupliques):
    $(document).ready(function() {
        document.getElementById('pcTable').addEventListener('click', function(e) {
            const btnReport = e.target.closest('.generate-pc-report-btn');
            if (btnReport) {
                e.preventDefault();
                const rowData = JSON.parse(btnReport.getAttribute('data-row'));
                // Petición al backend para obtener los datos completos del equipo
                fetch('index.php?view=pc&action=generateReport', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_equipo_informatico=' + encodeURIComponent(rowData.id_equipo_informatico)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && !data.error) {
                            // Crear formulario oculto y enviarlo a pcV-view.php
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'index.php?view=pcV'; // Usa el nombre correcto de la vista aquí
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
<script src="./js/pcTableSpecialActions.js"></script>