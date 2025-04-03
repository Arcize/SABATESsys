<aside class="navbar">
    <nav class="navbar__nav">
        <div class="navbar__header">
            <h1 class="page_header">SABATES</h1>
        </div>

        <div class="navbar__list-container">
            <div class="navbar__item">
                <a href="index.php?view=dashboard" class="navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <img src="app/views/img/dashboard.svg" alt="">
                        </div>
                        <div class="navbar__text">Panel</div>
                    </div>
                </a>
            </div>

            <div class="navbar__item">
                <a href="index.php?view=userTable" class="navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <img src="app/views/img/user_white.svg" alt="">
                        </div>
                        <div class="navbar__text">Usuarios</div>
                    </div>
                </a>
            </div>
            <div class="navbar__item">
                <a href="index.php?view=userTable" class="navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <img src="app/views/img/inventory.svg" alt="">
                        </div>
                        <div class="navbar__text">Inventario</div>
                    </div>
                </a>
            </div>

            <div class="navbar__item">
                <a class="accordion__header navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <img src="app/views/img/search.svg" alt="">
                        </div>
                        <div class="navbar__text">Consultar</div>
                    </div>
                    <div class="arrow">
                        <img src="app/views/img/arrow_drop_down_white.svg" alt="" class="imgArrow">
                    </div>
                </a>
            </div>
            <div class="accordion">
                <div class="accordion__content">
                    <div class="navbar__item">
                        <a href="index.php?view=employeeTable" class="navbar__link">
                            <div class="navbar__item-content">
                                <div class="navbar__icon">
                                    <img src="app/views/img/person.svg" alt="">
                                </div>
                                <div class="navbar__text">Empleados</div>
                            </div>
                        </a>
                    </div>
                    <div class="navbar__item">
                        <a href="index.php?view=pcTable" class="navbar__link">
                            <div class="navbar__item-content">
                                <div class="navbar__icon">
                                    <img src="app/views/img/computer.svg" alt="">
                                </div>
                                <div class="navbar__text">Equipos</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="navbar__item">
                <a class="accordion__header navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <img src="app/views/img/report.svg" alt="">
                        </div>
                        <div class="navbar__text">Reportes</div>
                    </div>
                    <div class="arrow">
                        <img src="app/views/img/arrow_drop_down_white.svg" alt="" class="imgArrow">
                    </div>
                </a>
            </div>
            <div class="accordion">
                <div class="accordion__content">
                    <div class="navbar__item">
                        <a href="index.php?view=faultReportTable" class="navbar__link">
                            <div class="navbar__item-content">
                                <div class="navbar__icon">
                                    <img src="app/views/img/fault.svg" alt="">
                                </div>
                                <div class="navbar__text">Fallas</div>
                            </div>
                        </a>
                    </div>
                    <div class="navbar__item">
                        <a href="" class="navbar__link">
                            <div class="navbar__item-content">
                                <div class="navbar__icon">
                                    <img src="app/views/img/activities.svg" alt="">
                                </div>
                                <div class="navbar__text">Actividades</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="navbar__item">
                <a href="#" class="navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <img src="app/views/img/bar_chart.svg" alt="">
                        </div>
                        <div class="navbar__text">Estadísticas</div>
                    </div>
                </a>
            </div>
        </div>
    </nav>
</aside>
<script src="app/views/js/collapseNavbar.js"></script>
<script src="app/views/js/accordion.js"></script>