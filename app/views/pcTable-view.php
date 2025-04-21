<?php

use app\controllers\PcController;

$pcController = new PcController();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Datos de Equipos Informáticos</h3>
        <div class="table-actions">
            <div class="table-filters">
                <div class="search-filter">
                    <div class="filter-dropdown">
                        <button class="dropdown-btn btn filter-button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                                <path d="M440-240q-17 0-28.5-11.5T400-280q0-17 11.5-28.5T440-320h80q17 0 28.5 11.5T560-280q0 17-11.5 28.5T520-240h-80ZM280-440q-17 0-28.5-11.5T240-480q0-17 11.5-28.5T280-520h400q17 0 28.5 11.5T720-480q0 17-11.5 28.5T680-440H280ZM160-640q-17 0-28.5-11.5T120-680q0-17 11.5-28.5T160-720h640q17 0 28.5 11.5T840-680q0 17-11.5 28.5T800-640H160Z" />
                            </svg>
                        </button>
                        <div class="dropdown-content filter-dropdown-menu">
                            <a href="#" class="dropdown-item">Opción 1</a>
                            <a href="#" class="dropdown-item">Opción 2</a>
                            <a href="#" class="dropdown-item">Opción 3</a>
                        </div>
                    </div>
                </div>
                <input type="text" class="input search-input" placeholder="Buscar...">
                <button class="search-button btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>
            <button class="table-button open-modal" data-modal="pcModal" data-fetch="false">Añadir Equipo</button>
        </div>
    </div>
    <table class="table" id="pcTable">
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
    <div class="table-footer">
        <div class="page-buttons">
            <button class="pagination-button prev btn">Anterior</button>
            <div class="pages"></div>
            <button class="pagination-button next btn">Siguiente</button>
        </div>
    </div>
</div>
<div class="overlay-modal">
    <div class="modal-box" id="pcModal">
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
                    <input type="hidden" id="id_pc" name="id_pc" class="inputKey">

                    <fieldset class="page-form slidePage">
                        <legend>
                            <h4 class="h4">Información General</h4>
                        </legend>
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante">Fabricante:</label>
                                <input type="text" id="fabricante" name="fabricante_equipo_informatico" class="input capitalize-first only-letters no-empty-after-space" required maxlength="35">
                            </div>
                            <div class="inputGroup">
                                <label for="estado">Estado del equipo:</label>
                                <select id="estado" name="id_estado_equipo" class="input" required>
                                    <option value="" selected>Seleccione</option>
                                    <option value="1">Operativo</option>
                                    <option value="2">Averiado</option>
                                    <option value="3">En reparación</option>
                                    <option value="4">En espera de piezas</option>
                                    <option value="5">Retirado</option>
                                </select>
                            </div>
                            <div class="inputGroup">
                                <label for="persona_id">ID de la persona asignada:</label>
                                <input type="text" id="persona_id" name="id_persona" class="input ci" required>
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
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante_ram">Fabricante de la RAM:</label>
                                <input type="text" id="fabricante_ram" name="fabricante_ram" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="tipo_ram">Tipo de RAM:</label>
                                <input type="text" id="tipo_ram" name="tipo_ram" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="frecuencia_ram">Frecuencia de la RAM (MHz):</label>
                                <input type="text" id="frecuencia_ram" name="frecuencia_ram" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="capacidad_ram">Capacidad de la RAM (GB):</label>
                                <input type="text" id="capacidad_ram" name="capacidad_ram" class="input" required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="page-form">
                        <legend>
                            <h4 class="h4">Almacenamiento</h4>
                        </legend>
                        <div class="pageInputs">
                            <div class="inputGroup">
                                <label for="fabricante_almacenamiento">Fabricante del almacenamiento:</label>
                                <input type="text" id="fabricante_almacenamiento" name="fabricante_almacenamiento" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="tipo_almacenamiento">Tipo de almacenamiento:</label>
                                <input type="text" id="tipo_almacenamiento" name="tipo_almacenamiento" class="input" required>
                            </div>
                            <div class="inputGroup">
                                <label for="capacidad_almacenamiento">Capacidad del almacenamiento (GB):</label>
                                <input type="text" id="capacidad_almacenamiento" name="capacidad_almacenamiento" class="input" required>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="modal-footer btnArea">
            <button class="modal-button close-modal" type="button" onclick="prevStep()">Volver</button>
            <button class="modal-button sentBtn" type="button" onclick="nextStep()">Siguiente</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        initializePagination("pcTable", "pc");
    });
</script>