<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Reportes de Fallas</h3>
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
            <button class="table-button open-modal" data-modal="faultReportModal" data-fetch="false">Crear Reporte</button>
        </div>
    </div>
    <table class="table" id="faultReportTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">ID Reporte</th>
                <th scope="col">Reportado Por</th>
                <th scope="col">ID Equipo</th>
                <th scope="col">Fecha / Hora</th>
                <th scope="col">Descripción</th>
                <th scope="col">Estado</th>
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
        <form action="index.php?view=faultReport&action=faultReport_fetch_create" method="POST" class="form" formType="faultReport">
            <div class="modal-body">
                <div class="userDetails">
                    <input type="hidden" id="id_fault_report" name="id_reporte_fallas" class="inputKey">
                    <div class="inputGroup">
                        <label for="cedulaPC">Cédula del Usuario:</label>
                        <input class="input ci" id="cedulaPC" required type="text" name="cedula" onkeyup="crossFields();">
                    </div>
                    <div class="inputGroup">
                        <label for="idPC">ID del Equipo:</label>
                        <input class="input numbers" id="idPC" required type="text" name="id_equipo_informatico" onkeyup="crossFields();">
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
    document.addEventListener("DOMContentLoaded", function() {
        initializePagination("faultReportTable", "faultReport");
    });
</script>