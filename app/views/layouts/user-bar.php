<header class="user-bar">

    <div class="nav-menu">
        <?php if (!isset($_SESSION["securityQuestions"])): ?>
            <button id="navbar-toggle-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                    <path d="M160-240q-17 0-28.5-11.5T120-280q0-17 11.5-28.5T160-320h640q17 0 28.5 11.5T840-280q0 17-11.5 28.5T800-240H160Zm0-200q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520h640q17 0 28.5 11.5T840-480q0 17-11.5 28.5T800-440H160Zm0-200q-17 0-28.5-11.5T120-680q0-17 11.5-28.5T160-720h640q17 0 28.5 11.5T840-680q0 17-11.5 28.5T800-640H160Z" />
                </svg>
            </button>
        <?php endif ?>
    </div>


    <div class="user-controls">
        <?php if (!isset($_SESSION["securityQuestions"])): ?>
            <div class="notifications">
                <button class="dropdown-button" onclick="toggleNotificationsMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                        <path d="M200-200q-17 0-28.5-11.5T160-240q0-17 11.5-28.5T200-280h40v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h40q17 0 28.5 11.5T800-240q0 17-11.5 28.5T760-200H200ZM480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z" />
                    </svg>
                </button>
                <div id="notifications-menu" class="dropdown-menu">
                    <div class="notification-list" id="notification-list">
                        <div style="text-align:center; color:#888; padding:1em;">Cargando notificaciones...</div>
                    </div>
                    <button id="see-all-notifications">Ver todas</button>
                </div>
            </div>
        <?php endif ?>

        <div class="user-dropdown">
            <button class="dropdown-button" onclick="toggleUserDropdown()">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                    <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121" class="dropdown-arrow">
                    <path d="M459-381 314-526q-3-3-4.5-6.5T308-540q0-8 5.5-14t14.5-6h304q9 0 14.5 6t5.5 14q0 2-6 14L501-381q-5 5-10 7t-11 2q-6 0-11-2t-10-7Z" />
                </svg>
            </button>
            <div id="user-menu" class="dropdown-menu">
                <?php if (!isset($_SESSION["securityQuestions"])): ?>
                    <a href="index.php?view=perfil">
                        <span>Perfil</span>
                    </a>
                    <?php if ($viewData['puede_ver_configuracion']): ?>

                        <a href="index.php?view=config">
                            <span>Configuración</span>
                        </a>
                    <?php endif ?>
                <?php endif ?>

                <a href="index.php?view=logout">
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </div>
    </div>
</header>

<?php
// Obtener el id_usuario y el id_rol de la sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
$id_rol = $_SESSION['id_rol'] ?? null;
$tipo_notificacion = null;
$id_destino = null;
if ($id_usuario && $id_rol) {
    // Si es admin o técnico, mostrar por rol, si es usuario normal, mostrar individuales
    if (isset($_SESSION['es_admin']) && $_SESSION['es_admin']) {
        $tipo_notificacion = 'rol';
        $id_destino = $id_rol;
    } else if (isset($_SESSION['es_tecnico']) && $_SESSION['es_tecnico']) {
        $tipo_notificacion = 'rol';
        $id_destino = $id_rol;
    } else {
        $tipo_notificacion = 'individual';
        $id_destino = $id_usuario;
    }
}
?>

<script>
    function fetchNotifications() {
        let tipo = 'individual';
        let id_destino = <?php echo isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 'null'; ?>;
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] == 1): ?>
                tipo = 'rol';
                id_destino = 1; // Administrador
            <?php elseif ($_SESSION['role'] == 3): ?>
                tipo = 'rol';
                id_destino = 3; // Técnico
            <?php endif; ?>
        <?php endif; ?>
        fetch(`index.php?view=notificaciones&action=fetch&tipo=${tipo}&id_destino=${id_destino}`)
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notification-list');
                const notifButton = document.querySelector('.notifications > button');
                if (!list) return;
                // Calcular cantidad de no leídas
                let unreadCount = 0;
                if (Array.isArray(data)) {
                    unreadCount = data.filter(n => !n.leida).length;
                }
                // Actualizar badge dinámico
                if (notifButton) {
                    if (unreadCount > 0) {
                        notifButton.setAttribute('data-count', unreadCount);
                    } else {
                        notifButton.removeAttribute('data-count');
                    }
                }
                if (!data || data.length === 0) {
                    list.innerHTML = '<div style="text-align:center; color:#888; padding:1em;">No hay notificaciones nuevas.</div>';
                    return;
                }
                list.innerHTML = data.map(n => {
                    // Extraer el código de reporte si existe en el mensaje o usar id_reporte_asociado
                    let codigoReporte = null;
                    if (n.codigo_reporte_fallas) {
                        codigoReporte = n.codigo_reporte_fallas;
                    } else {
                        // Intentar extraer del mensaje si no viene directo
                        const match = n.mensaje.match(/#([A-Za-z0-9\-_]+)/);
                        if (match) {
                            codigoReporte = match[1];
                        }
                    }
                    let href = '#';
                    if (codigoReporte) {
                        href = `index.php?view=faultReportTable&codigo=${encodeURIComponent(codigoReporte)}`;
                    }
                    return `
                        <a href="${href}" class="notification-item${n.leida ? '' : ' unread'}">
                            <div class="notification-content">
                                <div class="notification-header">
                                    <span class="notification-title">Nuevo Reporte de Falla</span>
                                    <span class="notification-date">${n.fecha_creacion ? new Date(n.fecha_creacion).toLocaleString('es-ES') : ''}</span>
                                </div>
                                <p class="notification-message">${n.mensaje}</p>
                            </div>
                        </a>
                    `;
                }).join('');
            })
            .catch(() => {
                const list = document.getElementById('notification-list');
                if (list) list.innerHTML = '<div style="text-align:center; color:#888; padding:1em;">Error al cargar notificaciones.</div>';
            });
    }
    setInterval(fetchNotifications, 5000);
    document.addEventListener('DOMContentLoaded', fetchNotifications);
</script>