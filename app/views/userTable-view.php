<?php
$imagenPath = '../public/img/banner_SABATES.png';
$imagenData = base64_encode(file_get_contents($imagenPath));
$logoBase64 = 'data:image/png;base64,' . $imagenData;

use app\controllers\RoleController;

$roleController = new RoleController();
$roles = $roleController->listRoles();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Usuarios</h3>
        <div class="table-actions">
            <!-- <a href="index.php?view=roleTable">
                <button class="table-button">Gestionar Roles</button>
            </a> -->
        </div>
    </div>
    <table class="table row-border hover" id="userTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Cédula</th>
                <th scope="col">Nombre Completo</th>
                <th scope="col">Departamento</th>
                <th scope="col">Rol</th>
            </tr>
        </thead>
        <tbody id="table-body" class="table-body">
            <!-- Aquí puedes cargar datos dinámicamente -->
        </tbody>
    </table>
</div>
<div class="overlay-modal">
    <div class="modal-box" id="userModal">
        <div class="modal-header">
            <h3 class="h3">Registrar un empleado</h3>
        </div>
        <form action="index.php?view=user&action=user_fetch_create" method="POST" class="form" formType="user">
            <div class="modal-body">
                <input type="hidden" id="id_usuario" name="id_usuario" class="inputKey">
                <div class="inputGroup">
                    <label for="username">Nombre de Usuario:</label>
                    <input class="input" id="username" type="text" name="username" readonly>
                </div>
                <div class="inputGroup">
                    <label for="cedula">Cedula:</label>
                    <input class="input" id="cedula" type="text" name="cedula" readonly>
                </div>
                <div class="inputGroup">
                    <label for="id_rol">Departamento:</label>
                    <select id="id_rol" required name="id_rol">
                        <option value="">Seleccione</option>
                        <?php foreach ($roles as $role) : ?>
                            <option value="<?php echo $role['id_rol']; ?>"><?php echo $role['rol']; ?></option>
                        <?php endforeach; ?>
                    </select>
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
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var rol = urlParams.get('rol');

        $('#userTable').DataTable({
            columnDefs: [{
                targets: [4],
                searchable: true,
                render: function(data, type, row) {
                    if (type === 'filter' && data && data.value !== "Administrador") {
                        // En el modo 'filter', devolvemos el valor seleccionado del select
                        return data.value;
                    }
                    // En otros modos ('display', 'type', 'sort'), devolvemos el HTML del select
                    if (data && data.value === "Administrador") {
                        return data.value;
                    } else if (data && data.options) {
                        let optionsHtml = data.options
                            .slice(1)
                            .map(rol => `
                                <option value="${rol.id_rol}" ${data.value === rol.rol ? "selected" : ""}>${rol.rol}</option>
                            `)
                            .join("");
                        return `
                            <select class="edit-on-change" data-user-id="${row.id_usuario}" data-field="rol">
                                ${optionsHtml}
                            </select>`;
                    }
                    return data; // En caso de que 'data' sea null o undefined
                }
            }],
            ...commonDatatableConfig,
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                filename: 'Reporte_Usuarios_' + new Date().toISOString().slice(0, 10),
                customize: function(doc) {
                    const logoBase64 = '<?php echo $logoBase64; ?>';
                    doc.header = function(currentPage, pageCount, pageSize) {
                        return {
                            image: logoBase64,
                            width: 150,
                            alignment: 'center',
                            margin: [0, 20, 0, 0]
                        };
                    };

                    // Si quieres ocultar columnas, ajusta los índices aquí. Por defecto, no ocultamos ninguna.
                    // const columnasOcultar = [5, 6];
                    // columnasOcultar.forEach(index => {
                    //     if (doc.content[1].table.body[0] && doc.content[1].table.body[0][index]) {
                    //         doc.content[1].table.body[0][index].text = '';
                    //     }
                    // });
                    // doc.content[1].table.body.forEach((row, rowIndex) => {
                    //     if (rowIndex > 0) {
                    //         columnasOcultar.forEach(index => {
                    //             if (row && row[index]) {
                    //                 row[index].text = '';
                    //             }
                    //         });
                    //     }
                    // });

                    // Eliminar cualquier otro elemento que parezca un título genérico
                    const sabatesIndex = doc.content.findIndex(element => (
                        typeof element.text === 'string' && element.text.includes('SABATES')
                    ));
                    if (sabatesIndex > -1) {
                        doc.content.splice(sabatesIndex, 1);
                    }
                    doc.content.splice(0, 0, {
                        text: 'Reporte de Usuarios',
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
                            columns: [
                                { text: 'SABATES ' + anioActual, alignment: 'left', margin: [40, 0, 0, 0], fontSize: 9, color: '#000000' },
                                { text: 'Reporte Generado el: ' + fechaHora, alignment: 'center', fontSize: 9, color: '#000000' },
                                { text: 'Página ' + currentPage.toString() + ' de ' + pageCount, alignment: 'right', margin: [0, 0, 40, 0], fontSize: 9, color: '#000000' }
                            ],
                            margin: [0, 0, 0, 10]
                        };
                    };
                },
                orientation: 'portrait',
                pageSize: 'A4',
                titleAttr: 'Exportar la tabla actual a PDF',
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            // Si es la columna de Rol (índice 4), toma el valor actual del select en la celda
                            if (column === 4 && node) {
                                var select = node.querySelector('select');
                                if (select) {
                                    return select.options[select.selectedIndex].text;
                                }
                                // Si no hay select, devuelve el texto plano
                                return node.textContent || data;
                            }
                            // Para otras columnas, devuelve el texto plano
                            return node ? node.textContent : data;
                        }
                    }
                }
            }
            // ...otros botones si es necesario...
            ],
            ajax: {
                url: 'index.php?view=user&action=user_fetch_page',
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
                    data: 'cedula'
                },
                {
                    data: 'nombre_completo'
                },
                {
                    data: 'nombre_departamento'
                },
                {
                    data: 'rol',
                    render: function(data, type, row) {
                        if (type === 'filter') {
                            // Solo devuelve el valor seleccionado para el filtro
                            return data.value;
                        }
                        if (data.value === "Administrador") {
                            return data.value;
                        } else {
                            let optionsHtml = data.options
                                .slice(1) // Omitir el primer elemento si es necesario
                                .map(rol => `
                    <option value="${rol.id_rol}" ${data.value === rol.rol ? "selected" : ""}>${rol.rol}</option>
                `)
                                .join("");

                            return `
                <select class="edit-on-change" data-user-id="${row.id_usuario}" data-field="rol" data-prev="${data.id}">
                    ${optionsHtml}
                </select>`;
                        }
                    }
                }
            ],
            initComplete: function() {
                // Aplicar el filtro si el parámetro 'rol' existe
                if (rol) {
                    this.api().search(rol).draw();
                }
            }

        });
    });
</script>
<script>
    document.addEventListener("change", function(event) {
        const selectElement = event.target.closest("td select.edit-on-change");
        if (selectElement) {
            const userId = selectElement.dataset.userId;
            const fieldName = selectElement.dataset.field;
            const newValue = selectElement.value;
            const previousValue = selectElement.getAttribute("data-prev") || "";

            // Restaurar el valor anterior si el usuario cancela
            function revertSelect() {
                if (previousValue) {
                    selectElement.value = previousValue;
                }
            }

            // Pregunta de confirmación con SweetAlert
            if (window.Swal) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¿Desea cambiar el rol de este usuario?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'swal2-popup' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ...AJAX para actualizar...
                        fetch(
                                `index.php?view=user&action=user_fetch_update&id_usuario=${userId}`, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/x-www-form-urlencoded",
                                    },
                                    body: `id_rol=${encodeURIComponent(newValue)}`,
                                }
                            )
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.success) {
                                    selectElement.setAttribute("data-prev", newValue);
                                    const cell = selectElement.closest("td");
                                    cell.classList.add("updated");
                                    setTimeout(() => cell.classList.remove("updated"), 1000);
                                    console.log(data.message);
                                    // Alerta de éxito mejorada
                                    if (window.Swal) {
                                        Swal.fire({
                                            title: "¡Éxito!",
                                            text: "El rol del usuario ha sido actualizado.",
                                            icon: "success",
                                            timer: 2000,
                                            showConfirmButton: false,
                                            customClass: {
                                                popup: "custom-swal-font"
                                            }
                                        });
                                    }
                                } else {
                                    revertSelect();
                                    console.error("Error al guardar:", data.message);
                                }
                            })
                            .catch((error) => {
                                revertSelect();
                                console.error("Error de red:", error);
                            });
                    } else {
                        revertSelect();
                    }
                });
            } else {
                // Si no hay SweetAlert, continuar como antes
                fetch(
                        `index.php?view=user&action=user_fetch_update&id_usuario=${userId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: `id_rol=${encodeURIComponent(newValue)}`,
                        }
                    )
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            selectElement.setAttribute("data-prev", newValue);
                            const cell = selectElement.closest("td");
                            cell.classList.add("updated");
                            setTimeout(() => cell.classList.remove("updated"), 1000);
                            console.log(data.message);
                            // Alerta de éxito mejorada
                            if (window.Swal) {
                                Swal.fire({
                                    title: "¡Éxito!",
                                    text: "El rol del usuario ha sido actualizado.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: "custom-swal-font"
                                    }
                                });
                            }
                        } else {
                            revertSelect();
                            console.error("Error al guardar:", data.message);
                        }
                    })
                    .catch((error) => {
                        revertSelect();
                        console.error("Error de red:", error);
                    });
            }
        }
    });

    // Guardar el valor inicial del select al cargar la tabla
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("td select.edit-on-change").forEach(function(select) {
            select.setAttribute("data-prev", select.value);
        });
    });
</script>