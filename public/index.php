<?php
/*
 * Punto de entrada principal para la aplicación.
 * 
 * Este script maneja el enrutamiento y la gestión de sesiones para la aplicación.
 * Incluye los controladores y vistas necesarios según los parámetros de la URL.
 * 
 * Controladores Incluidos:
 * - SessionController: Gestiona las sesiones de usuario (inicio de sesión, cierre de sesión).
 * - ViewController: Determina qué vista mostrar.
 * - EmployeeController: Maneja solicitudes relacionadas con empleados.
 * - PcController: Maneja solicitudes relacionadas con PCs.
 * - UserController: Gestiona el registro de usuarios.
 * 
 * Enrutamiento de URL:
 * - Si el parámetro 'view' está establecido en la URL, se utiliza para determinar la vista solicitada.
 * - Si el parámetro 'view' no está establecido, por defecto se muestra "login".
 * 
 * Manejo de Solicitudes:
 * - Maneja el registro e inicio de sesión de usuarios a través de solicitudes POST.
 * - Maneja solicitudes de cierre de sesión.
 * - Redirige las solicitudes a los controladores apropiados según el parámetro 'view'.
 * 
 * Gestión de Sesiones:
 * - Verifica si el usuario ha iniciado sesión.
 * - Redirige a los usuarios que han iniciado sesión lejos de las vistas de inicio de sesión y registro al dashboard.
 * - Restringe el acceso a ciertas vistas si el usuario no ha iniciado sesión.
 * 
 * Vistas:
 * - Incluye las vistas de head y navbar.
 * - Carga la vista apropiada según el parámetro 'view'.
 * - Redirige al inicio de sesión si se intenta acceder a vistas restringidas sin haber iniciado sesión.
*/
if (session_status() == PHP_SESSION_NONE) {
    session_name("SesionUsuario");
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\ViewController;
use app\controllers\SessionController;
use app\controllers\EmployeeController;
use app\controllers\PcController;
use app\controllers\UserController;
use app\controllers\FaultReportController;
use app\controllers\ChartController;

$SessionController = new SessionController();

if (isset($_GET['view'])) {
    $url = explode("/", $_GET['view']);
} else {
    $url = ["login"];
}

if (isset($_GET['view']) && $_GET['view'] === 'chartData') {
    $chartController = new ChartController();
    $chartController->chartData();
    exit();
}
if ($url[0] == "employee") {
    $employeeController = new EmployeeController();
    $employeeController->handleRequestEmployee();
    exit();
}
if ($url[0] == "pc") {
    $pcController = new PcController();
    $pcController->handleRequestPC();
    exit();
}
if ($url[0] == "faultReport") {
    $faultReportController = new faultReportController();
    $faultReportController->handleRequestFaultReport();
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once __DIR__ . '/../app/views/layouts/head.php'; ?>
</head>

<body>
    <?php

    if ($url[0] == "register_user" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $userController = new UserController();
        $userController->registerUser();
        exit();
    } elseif ($url[0] == "login_user" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $SessionController->login($username, $password);
        exit();
    } elseif ($url[0] == "logout") {
        $SessionController->logout();
        exit();
    } else {
        $viewController = new ViewController();
        $view = $viewController->getViewController($url[0]);

        if ($SessionController->isLoggedIn()) {
            if ($url[0] == "login" || $url[0] == "register") {
                // Evita redirecciones innecesarias si ya está logueado y se intenta acceder a login o register
                header("Location: index.php?view=dashboard");
                exit();
            } else {
    ?>
                <div class="layout">
                    <?php
                    // Si ya está logueado y accede a cualquier otra vista permitida
                    require_once __DIR__ . '/../app/views/layouts/navbar.php';
                    ?>

                    <div class="principal">
                        <div class="content">
                            <?php require_once __DIR__ . '/../app/views/layouts/user-bar.php';
                            if (in_array($url[0], ['employeeTable', 'pcTable', 'faultReportTable'])) {
                                echo '<script src="./js/pagination.js"></script>';
                                echo '<script src="./js/filterSearch.js"></script>';
                                echo '<script src="./js/modal.js"></script>';
                            }

                            ?>

                            <?php
                            require_once $view;
                            if ($url[0] == 'bulkDataLoad') {
                                echo '<script src="./js/fileUpload.js"></script>';
                            }
                            ?>

                            <script src="./js/customAlerts.js"></script>
                        </div>
                    </div>
                </div>
    <?php

            }
        } else {
            $restricted_views = [
                "dashboard",
                "config",
                "employeeTable",
                "pcTable",
                "pcForm",
                "pcFormEdit",
                "userTable",
                "roleTable",
                "roleForm",
                "faultReportTable"
            ];

            if (in_array($url[0], $restricted_views)) {
                // Si no está logueado y trata de acceder a vistas restringidas, redirige a login
                header("Location: index.php?view=login");
                exit();
            } else {
                // Si no está logueado, permite acceso a vistas públicas
                require_once $view;
            }
        }
    }


    ?>
    <script src="./js/formValidation.js"></script>

</body>

</html>