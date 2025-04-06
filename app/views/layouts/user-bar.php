<header class="user-bar">
    <div class="nav-menu">
        <button onclick="toggleNavbar()"><img src="app/views/img/menu.svg" alt="Menú"></button>
    </div>
    <div class="user-controls">
        <div class="notifications">
            <button class="dropdown-button" onclick="toggleNotificationsMenu()">
                <img src="app/views/img/notification.svg" alt="Notificaciones">
            </button>
            <div id="notifications-menu" class="dropdown-menu">
                <a href="#">
                    <div>
                        <span>Reporte de Falla</span>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </a>
                <a href="#">
                    <span>Notificación 2</span>
                </a>
                <a href="#">
                    <span>Notificación 3</span>
                </a>
            </div>
        </div>
        <div class="user-dropdown">
            <button class="dropdown-button" onclick="toggleUserDropdown()">
                <img src="app/views/img/user.svg" alt="Usuario">
                <img src="app/views/img/arrow_drop_down.svg" alt="Flecha" class="dropdown-arrow">
            </button>
            <div id="user-menu" class="dropdown-menu">
                <a href="#">
                    <span>Perfil</span>
                </a>
                <a href="index.php?view=config">
                    <span>Configuración</span>
                </a>
                <a href="index.php?view=logout">
                    <span>Cerrar sesión</span>
                    <img src="app/views/img/logout.svg" alt="Cerrar sesión">
                </a>
            </div>
        </div>
    </div>
</header>