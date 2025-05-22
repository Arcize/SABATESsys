<?php

use app\controllers\StatePcController;

$statePcController = new StatePcController();
$statePcController = $statePcController->listStates();
?>
<style>
    #pcModal{
        max-height: 90vh;
    }

    #ram-modules, #storage-modules {
    max-height: 300px;   /* Ajusta según tu diseño */
    overflow-y: auto;
    margin-bottom: 1em;
}
</style>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Equipos Informáticos</h3>
        <div class="table-actions">
            <button class="table-button open-modal" data-target-modal="pcModal" data-fetch="false">Añadir Equipo</button>
        </div>
    </div>
    <table class="table row-border hover" id="pcTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Fabricante</th>
                <th scope="col">Estado</th>
                <th scope="col">Asignado a</th>
                <th scope="col">Procesador</th>
                <th scope="col">Motherboard</th>
                <th scope="col">Fuente</th>
                <th scope="col">RAM</th>
                <th scope="col">Almacenamiento</th>
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
                            <h4 class="h4">Información General</h4>
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
                            <div class="inputGroup">
                                <label for="cedula">Cédula de la Persona Asignada:</label>
                                <input type="text" id="cedula" name="cedula" class="input ci" required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <h4 class="h4">Procesador</h4>
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
                            <h4 class="h4">Motherboard</h4>
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
                            <h4 class="h4">Fuente</h4>
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
                            <h4 class="h4">RAM</h4>
                        </legend>
                        <div id="ram-modules">
                            <div class="ram-module">
                                <h5 class="ram-title">Módulo 1</h5>
                                <div class="inputGroup">
                                    <label>Fabricante de la RAM:</label>
                                    <input type="text" name="fabricante_ram[]" class="input" required>
                                </div>
                                <div class="inputGroup">
                                    <label>Tipo de RAM:</label>
                                    <input type="text" name="tipo_ram[]" class="input" required>
                                </div>
                                <div class="inputGroup">
                                    <label>Frecuencia de la RAM (MHz):</label>
                                    <input type="text" name="frecuencia_ram[]" class="input" required>
                                </div>
                                <div class="inputGroup">
                                    <label>Capacidad de la RAM (GB):</label>
                                    <input type="text" name="capacidad_ram[]" class="input" required>
                                </div>
                                <button type="button" class="remove-ram btn-mini">Quitar</button>
                            </div>
                        </div>
                        <button type="button" id="add-ram" class="btn-mini">Agregar módulo RAM</button>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <h4 class="h4">Almacenamiento</h4>
                        </legend>
                        <div id="storage-modules">
                            <div class="storage-module">
                                <h5 class="storage-title">Módulo 1</h5>
                                <div class="inputGroup">
                                    <label for="fabricante_almacenamiento">Fabricante del almacenamiento:</label>
                                    <input type="text" id="fabricante_almacenamiento" name="fabricante_almacenamiento[]" class="input" required>
                                </div>
                                <div class="inputGroup">
                                    <label for="tipo_almacenamiento">Tipo de almacenamiento:</label>
                                    <input type="text" id="tipo_almacenamiento" name="tipo_almacenamiento[]" class="input" required>
                                </div>
                                <div class="inputGroup">
                                    <label for="capacidad_almacenamiento">Capacidad del almacenamiento (GB):</label>
                                    <input type="text" id="capacidad_almacenamiento" name="capacidad_almacenamiento[]" class="input" required>
                                </div>
                                <button type="button" class="remove-storage btn-mini">Quitar</button>
                            </div>
                        </div>
                        <button type="button" id="add-storage" class="btn-mini">Agregar almacenamiento</button>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="modal-footer btnArea">
            <button class="modal-button prevBtn" type="button">Cancelar</button>
            <button class="modal-button sentBtn" type="button" >Siguiente</button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#pcTable').DataTable({
            ...commonDatatableConfig, // Configuración común
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
                    data: 'fabricante_equipo_informatico'
                },
                {
                    data: 'estado_equipo_informatico'
                },
                {
                    data: 'nombre_completo'
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
                {
                    data: 'capacidad_ram_total'
                },
                {
                    data: 'almacenamiento_total'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div class="button-container">
                            <button class="crud-button green-button open-modal" data-fetch="true" data-target-modal="pcModal" data-id="${data.id_equipo_informatico}">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                            </button>
                            <button class="crud-button red-button" onclick="confirmDelete(${data.id_equipo_informatico}, 'pc', 'id_pc')">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                            </button>
                        </div>`;
                    }
                }
            ]
        });
    });
</script>