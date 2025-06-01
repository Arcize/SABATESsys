<?php

namespace app\controllers;

use app\controllers\UserController;

$userController = new UserController;
?>

<aside class="navbar navbar-loading">
    <nav class="navbar__nav">
        <div class="navbar__header">
            <h1 class="page_header">SABATES</h1>
        </div>

        <div class="navbar__list-container">
            <div class="navbar__item">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                    <a href="index.php?view=dashboard" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                                    <path d="M160-200v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H600q-17 0-28.5-11.5T560-160v-200q0-17-11.5-28.5T520-400h-80q-17 0-28.5 11.5T400-360v200q0 17-11.5 28.5T360-120H240q-33 0-56.5-23.5T160-200Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Panel</div>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="index.php?view=inicio" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                                    <path d="M160-200v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H600q-17 0-28.5-11.5T560-160v-200q0-17-11.5-28.5T520-400h-80q-17 0-28.5 11.5T400-360v200q0 17-11.5 28.5T360-120H240q-33 0-56.5-23.5T160-200Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Inicio</div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            <?php if ($viewData['puede_ver_usuarios']): ?>
                <div class="navbar__item">
                    <a href="index.php?view=userTable" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                    <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Usuarios</div>
                        </div>
                    </a>
                </div>
            <?php endif ?>
            <!-- <?php if ($viewData['puede_ver_inventario']): ?>
                <div class="navbar__item">
                    <a href="index.php?view=inventory" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                                    <path d="M200-80q-33 0-56.5-23.5T120-160v-451q-18-11-29-28.5T80-680v-120q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v120q0 23-11 40.5T840-611v451q0 33-23.5 56.5T760-80H200Zm0-520v440h560v-440H200Zm-40-80h640v-120H160v120Zm240 280h160q17 0 28.5-11.5T600-440q0-17-11.5-28.5T560-480H400q-17 0-28.5 11.5T360-440q0 17 11.5 28.5T400-400Zm80 20Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Inventario</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?> -->


            <?php if ($viewData['puede_ver_empleados']): ?>
                <div class="navbar__item">
                    <a href="index.php?view=employeeTable" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                                    <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Empleados</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($viewData['puede_ver_equipos']): ?>
                <div class="navbar__item">
                    <a href="index.php?view=pcTable" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#f6f6f6">
                                    <path d="M80-120q-17 0-28.5-11.5T40-160q0-17 11.5-28.5T80-200h800q17 0 28.5 11.5T920-160q0 17-11.5 28.5T880-120H80Zm80-120q-33 0-56.5-23.5T80-320v-440q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v440q0 33-23.5 56.5T800-240H160Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Equipos</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <div class="navbar__item">
                <a href="index.php?view=faultReportTable" class="navbar__link">
                    <div class="navbar__item-content">
                        <div class="navbar__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                                <path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm0-160q17 0 28.5-11.5T520-480v-160q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640v160q0 17 11.5 28.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                            </svg>
                        </div>
                        <div class="navbar__text">Fallas</div>
                    </div>
                </a>
            </div>
            <?php if ($viewData['puede_ver_actividad']): ?>

                <div class="navbar__item">
                    <a href="index.php?view=activitiesReportTable" class="navbar__link">
                        <div class="navbar__item-content">
                            <div class="navbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                                    <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm280-240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z" />
                                </svg>
                            </div>
                            <div class="navbar__text">Actividades</div>
                        </div>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </nav>
</aside>
<script src="./js/accordion.js"></script>
<script src="./js/collapseNavbar.js"></script>