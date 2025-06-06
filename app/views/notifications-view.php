<div class="view-box">
    <div class="table-heading">
        <h3 class="h3">Todas las notificaciones</h3>
    </div>
    <div id="all-notifications-list" class="notification-list" style="max-width:600px;margin:auto;"></div>
</div>
<script>
    function fetchAllNotifications() {
        let tipo = '<?php echo $tipo_notificacion; ?>';
        let id_destino = <?php echo (int)$id_destino; ?>;
        fetch(`index.php?view=notificaciones&action=fetch_all&tipo=${tipo}&id_destino=${id_destino}`)
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('all-notifications-list');
                if (!data || data.length === 0) {
                    list.innerHTML = '<div style="text-align:center; color:#888; padding:1em;">No hay notificaciones.</div>';
                    return;
                }
                list.innerHTML = data.map(n => {
                    let codigoReporte = n.codigo_reporte_fallas || (n.mensaje.match(/#([A-Za-z0-9\-_]+)/) ? n.mensaje.match(/#([A-Za-z0-9\-_]+)/)[1] : null);
                    let href = codigoReporte ? `index.php?view=faultReportTable&codigo=${encodeURIComponent(codigoReporte)}` : '#';
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
                const list = document.getElementById('all-notifications-list');
                if (list) list.innerHTML = '<div style="text-align:center; color:#888; padding:1em;">Error al cargar notificaciones.</div>';
            });
    }
    document.addEventListener('DOMContentLoaded', fetchAllNotifications);
</script>
<style>
    #all-notifications-list.notification-list {
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow-y: hidden;
        max-height: fit-content;
    }

    #all-notifications-list .notification-item {
        display: block;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1em;
        background: #fff;
        text-decoration: none;
        color: #222;
        transition: box-shadow 0.2s;
        height: fit-content;
    }

    #all-notifications-list .notification-item.unread {
        background: #f5faff;
        border-left: 4px solid #ffc107;
    }

    #all-notifications-list .notification-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
    }

    #all-notifications-list .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5em;
    }

    #all-notifications-list .notification-title {
        font-weight: bold;
    }

    #all-notifications-list .notification-date {
        font-size: 0.95em;
        color: #888;
    }

    #all-notifications-list .notification-message {
        margin: 0;
    }
</style>