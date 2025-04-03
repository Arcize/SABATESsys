<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Reportes de Fallas</h3>
        <div class="table-actions">
            <div class="table-filters">
                <div class="search-filter">
                    <div class="filter-dropdown">
                        <button class="dropdown-btn btn filter-button">
                            <img src="app/views/img/filter.svg" alt="Filter">
                        </button>
                        <div class="dropdown-content filter-dropdown-menu">
                            <a href="#" class="dropdown-item">Opción 1</a>
                            <a href="#" class="dropdown-item">Opción 2</a>
                            <a href="#" class="dropdown-item">Opción 3</a>
                        </div>
                    </div>
                </div>
                <input type="text" class="input search-input" placeholder="Buscar...">
                <button class="search-button btn"><img src="app/views/img/search.svg" alt="Search"></button>
            </div>
            <button class="table-button open-modal" data-modal="faultReportModal" data-fetch="false">Crear Reporte</button>
        </div>
    </div>
    <table class="table">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cédula</th>
                <th scope="col">Departamento</th>
                <th scope="col">Sexo</th>
                <!-- <th scope="col">ID Usuario</th> -->
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">

        </tbody>
    </table>
    <div class="table-footer">
        <div class="page-buttons">
            <button class="pagination-button prev btn">Anterior</button>
            <div class="pages">
            </div>
            <button class="pagination-button next btn">Siguiente</button>
        </div>
    </div>
</div>
<div class="overlay-modal">
    <div class="modal-box" id="faultReportModal">
        <div class="modal-header">
            <h3 class="h3">Crear Reporte de Falla</h3>
        </div>
        <form action="index.php?view=faultReport&action=create_report" method="POST" class="form">
            <div class="modal-body">
                <div class="userDetails">
                <input type="hidden" id="id_fault_report" name="id_fault_report" class="inputKey">
                    <div class="inputGroup">
                        <label for="cedulaPC">Cédula del Usuario:</label>
                        <input class="input ci" id="cedulaPC" required type="text" name="cedulaPC" onkeyup="crossFields();">
                    </div>
                    <div class="inputGroup">
                        <label for="idPC">ID del Equipo:</label>
                        <input class="input numbers" id="idPC" required type="text" name="idPC" onkeyup="crossFields();">
                    </div>
                </div>
                <div class="inputGroup textArea">
                    <label for="contentFaultReport">Descripción de la Falla:</label>
                    <textarea id="contentFaultReport" required name="contentFaultReport"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button class="modal-button close-modal" type="button">Cancelar</button>
                <button class="modal-button sentBtn" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>