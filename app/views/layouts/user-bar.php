<header class="user-bar">
    <div class="menu">
        <button onclick="toggleNavbar()"><img src="app/views/img/menu.svg" alt=""></button>
    </div>
    <div class="user-panel">
        <div class="notifications">
            <button class="dropbtn" onclick="toggleNotificationsDropdown()">
                <img src="app/views/img/notification.svg" alt="">
            </button>
            <div id="notificationsDropdown" class="dropdown-content">
                <a href="#">
                    <span>Notificación 1</span>
                </a>
                <a href="#">
                    <span>Notificación 2</span>
                </a>
                <a href="#">
                    <span>Notificación 3</span>
                </a>
            </div>
        </div>
        <div class="user">
            <button class="dropbtn" onclick="toggleDropdown()">
                <img src="app/views/img/user.svg" alt="">
                <img src="app/views/img/arrow_drop_down.svg" alt="" class="dropdownImg">
            </button>
            <div id="dropdown" class="dropdown-content">
                <a href="#">
                    <span>Perfil</span>
                </a>
                <a href="index.php?view=config">
                    <span>Configuración</span>
                </a>
                <a href="index.php?view=logout">
                    <span>Cerrar sesión</span>
                    <img src="app/views/img/logout.svg" alt="">
                </a>
            </div>
        </div>
    </div>
</header>