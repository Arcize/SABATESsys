<?php
// Vista: pcDeincorporatedTable-view.php
?>
<style>
    /* Igual que en la vista principal */
    #pcDeincorporatedTable {
        width: 100% !important;
    }
</style>
<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Equipos Desincorporados</h3>
        <div class="table-actions">
            <a href="index.php?view=pcTable">
                <button type="button" class="table-button dark-button">Volver</button>
            </a>
        </div>
    </div>
    <table class="table row-border hover" id="pcDeincorporatedTable">
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
        <tbody id="table-body-deincorporated" class="table-body">
            <!-- Los datos se cargarán dinámicamente -->
        </tbody>
    </table>
</div>
<script src="./js/datatableConfig.js"></script>
<script>
// Variable global para permisos (igual que en pcTable)
window.userIsAdmin = <?php echo (isset($_SESSION['role']) && $_SESSION['role'] == 1) ? 'true' : 'false'; ?>;

$(document).ready(function() {
    $('#pcDeincorporatedTable').DataTable({
        ...commonDatatableConfig,
        ajax: {
            url: 'index.php?view=pc&action=pc_fetch_deincorporated',
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
            { data: 'fabricante_equipo_informatico' },
            { 
                data: 'estado_equipo_informatico',
                render: function(data) {
                    return `<span class="states dark-button">${data}</span>`;
                }
            },
            { 
                data: 'nombre_completo',
                render: function(data) {
                    if (!data || data === '' || data === null) {
                        return '<span class="states dark-button">Sin asignar</span>';
                    }
                    return data;
                }
            },
            { data: 'nombre_procesador' },
            { data: 'motherboard' },
            { data: 'fuente' },
            { data: 'capacidad_ram_total' },
            { data: 'almacenamiento_total' },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    let actions = `<div class="actions-dropdown-container">
                        <button class="crud-button actions-dropdown-btn dark-button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>
                        </button>
                        <div class="actions-dropdown-menu" style="display:none; position:absolute; z-index:10;">
                            <button class="crud-button crud-option generate-pc-report-btn" data-row='${JSON.stringify(data)}'>Generar Reporte</button>`;
                    if (window.userIsAdmin) {
                        actions += `<button class="crud-button crud-option reincorporate-pc-btn" data-id="${data.id_equipo_informatico}">Reincorporar equipo</button>`;
                    }
                    actions += `</div></div>`;
                    return actions;
                }
            }
        ]
    });
});

// Dropdown de acciones igual que en la tabla principal
$(document).ready(function() {
    document.getElementById('pcDeincorporatedTable').addEventListener('click', function(e) {
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

// Acción para reincorporar equipo (igual que en la tabla principal)
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
            customClass: { popup: 'swal2-popup' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('index.php?view=pc&action=reincorporate_pc', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_equipo_informatico=${encodeURIComponent(id)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Reincorporado', data.message, 'success');
                            $('#pcDeincorporatedTable').DataTable().ajax.reload(null, false);
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

// Acción para generar reporte (igual que en la tabla principal)
$(document).ready(function() {
    document.getElementById('pcDeincorporatedTable').addEventListener('click', function(e) {
        const btnReport = e.target.closest('.generate-pc-report-btn');
        if (btnReport) {
            e.preventDefault();
            const rowData = JSON.parse(btnReport.getAttribute('data-row'));
            fetch('index.php?view=pc&action=generateReport', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id_equipo_informatico=' + encodeURIComponent(rowData.id_equipo_informatico)
                })
                .then(response => response.json())
                .then(data => {
                    if (data && !data.error) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php?view=pcV';
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'row';
                        input.value = JSON.stringify(data);
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        if (window.Swal) {
                            Swal.fire({ icon: 'error', title: 'Error', text: data.error || 'No se pudo generar el reporte.' });
                        } else {
                            alert(data.error || 'No se pudo generar el reporte.');
                        }
                    }
                })
                .catch(error => {
                    if (window.Swal) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo generar el reporte.' });
                    } else {
                        alert('No se pudo generar el reporte.');
                    }
                });
        }
    });
});
</script>
<!-- Incluir scripts de acciones especiales si existen -->
<script src="./js/pcTableSpecialActions.js"></script>
