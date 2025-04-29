<?php

use app\controllers\RoleController;

$roleController = new RoleController();
$roles = $roleController->listRoles();
?>

<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Usuarios</h3>
        <div class="table-actions">
            <a href="index.php?view=roleTable">
                <button class="table-button">Gestionar Roles</button>
            </a>
        </div>
    </div>
    <table class="table row-border hover" id="userTable">
        <thead class="table-head">
            <tr>
                <th scope="col">N°</th>
                <th scope="col">Usuario</th>
                <th scope="col">Cédula</th>
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
        $('#userTable').DataTable({
            ...commonDatatableConfig, // Configuración común
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
                    data: 'username'
                },
                {
                    data: 'cedula'
                },
                {
                    data: 'rol',
                    render: function(data, type, row) {
                        let optionsHtml = data.options
                            .map(rol => `
                <option value="${rol.id_rol}" ${data.value === rol.rol ? "selected" : ""}>${rol.rol}</option>
            `)
                            .join("");

                        return `
            <select class="edit-on-change" data-user-id="${row.id_usuario}" data-field="rol">
                ${optionsHtml}
            </select>`;
                    }
                }
            ]
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

            console.log(
                `Cambiando ${fieldName} para el usuario ${userId} al id_rol: ${newValue}`
            );

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
                        const cell = selectElement.closest("td");
                        cell.classList.add("updated");
                        setTimeout(() => cell.classList.remove("updated"), 1000);
                        console.log(data.message);
                    } else {
                        console.error("Error al guardar:", data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error de red:", error);
                });
        }
    });
</script>