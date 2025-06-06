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
define('APP_ROOT_PATH', dirname(__DIR__));
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
use app\controllers\BulkUploadController;
use app\controllers\SecurityQuestionsController;
use app\controllers\ActivitiesReportController;
use app\controllers\UploadController;
use app\controllers\NotificacionesController;

use app\helpers\PermissionHelper;
use app\helpers\Security; // Importa la clase Security
use app\helpers\PdfHelper;
use Dompdf\Helpers;

// Inicializa los controladores que se usan globalmente
$SessionController = new SessionController();
$userController = new UserController();

// Obtén los permisos para la navbar
$navbarPermissions = PermissionHelper::getNavbarPermissions();
$viewData = $navbarPermissions;

if (isset($_GET['view'])) {
    $url = explode("/", $_GET['view']);
} else {
    $url = ["login"];
}

$viewName = $url[0]; // Obtén el nombre de la vista

// Verifica el acceso a la vista usando la clase Security
Security::checkViewAccess($viewName);

if ($viewName === 'chartsData') {
    $chartController = new ChartController();
    $chartController->chartsData();
    exit();
}
if ($viewName === 'bulkUpload') {
    $bulkUploadController = new BulkUploadController();
    $bulkUploadController->handleRequestBulkUpload();
    exit();
}
if ($viewName === "employee") {
    $employeeController = new EmployeeController();
    $employeeController->handleRequestEmployee();
    exit();
}
if ($viewName === "pc") {
    $pcController = new PcController();
    $pcController->handleRequestPC();
    exit();
}
if ($viewName === "faultReport") {
    $faultReportController = new FaultReportController();
    $faultReportController->handleRequestFaultReport();
    exit();
}
if ($viewName === "securityQuestions") {
    $securityQuestionsController = new SecurityQuestionsController();
    $securityQuestionsController->handleRequestSecurityQuestions();
    exit();
}
if ($viewName === "user") {
    $userController = new UserController();
    $userController->handleRequestUser();
    exit();
}
if ($viewName === "activitiesReport") {
    $activitiesReportController = new ActivitiesReportController();
    $activitiesReportController->handleRequestActivitiesReport();
    exit();
}
if ($viewName === "notificaciones") {
    $notificacionesController = new NotificacionesController();
    $notificacionesController->handleRequest();
    exit();
}
if ($viewName === "upload") {
    $uploadController = new UploadController();
    $uploadController->handleUpload();
    exit();
}
if ($viewName === "pdf") {
    PdfHelper::generatePdf($_POST['htmlContent']);
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

    if ($viewName == "register_user" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $userController = new UserController();
        $userController->registerUser();
        exit();
    } elseif ($viewName == "login_user" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $cedula = $_POST['cedula'];
        $password = $_POST['password'];
        $SessionController->login($cedula, $password);
        exit();
    } elseif ($viewName == "logout") {
        $SessionController->logout();
        exit();
    } else {
        $viewController = new ViewController();
        $view = $viewController->getViewController($viewName);

        if ($SessionController->isLoggedIn()) {
            if ($viewName == "login" || $viewName == "register" || $viewName == "forgotPassword") {
                // Evita redirecciones innecesarias si ya está logueado y se intenta acceder a login o register
                if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
                    header("Location: index.php?view=dashboard");
                } else {
                    header("Location: index.php?view=inicio");
                }
                exit();
            } else {
                if (!$userController->isSecurityQuestionsSetup()) {
                    $_SESSION['securityQuestions'] = false;
    ?>
                    <div class="principal">
                        <div class="content">
                            <?php
                            require_once __DIR__ . '/../app/views/layouts/user-bar.php';

                            require_once __DIR__ . '/../app/views/layouts/securityQuestions.php';
                            exit();
                            ?>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="layout">
                        <?php
                        // Si ya está logueado y accede a cualquier otra vista permitida
                        require_once __DIR__ . '/../app/views/layouts/navbar.php';
                        ?>

                        <div class="principal">
                            <div class="content">
                                <?php require_once __DIR__ . '/../app/views/layouts/user-bar.php'; ?>

                                <?php
                                require_once $view;
                                if (in_array($viewName, ['employeeTable', 'pcTable', 'faultReportTable', 'userTable', 'activitiesReportTable', 'myFaultReports'])) {
                                    echo '<script src="./js/datatableConfig.js"></script>';
                                    echo '<script src="./js/dropzone.min.js"></script>';
                                    echo '<script src="./js/modal.js"></script>';
                                    echo '<script src="./js/customAlerts.js"></script>';
                                    echo '<script src="./js/dataTables.dateTime.min.js"></script>';
                                    // echo '<script src="./js/dataTables.responsive.min.js"></script>';

                                }
                                if (in_array($viewName, ['faultReportTable', 'userTable'])) {
                                    echo '<script src="./js/tableSpecialActions.js"></script>';
                                }
                                if ($viewName == 'activitiesReportTable') {
                                    // echo '<script src="./js/dropzone.min.js"></script>';
                                    // echo '<script src="./js/dropzoneActivities.js"></script>';
                                }
                                if ($viewName == 'bulkDataLoad') {
                                    echo '<script src="./js/fileUpload.js"></script>';
                                }
                                if ($viewName == 'pcTable') {
                                    echo '<script src="./js/multi_step_form.js"></script>';
                                }

                                if ($viewName == 'dashboard') {
                                    echo '<script src="./js/chart.umd.js"></script>';
                                }
                                // --- NUEVO: Comprobación de estado de empleado ---
                                echo '<script src="./js/employeeSessionCheck.js"></script>';
                                ?>

                            </div>
                        </div>
                    </div>
    <?php
                }
            }
        } else {
            // Si no está logueado, permite acceso solo a ciertas vistas
            echo '<script src="./js/forgotPassword.js"></script>';

            $publicViews = ["login", "register", "forgotPassword"]; // Añade aquí las vistas públicas
            if (!in_array($viewName, $publicViews)) {
                header("Location: index.php?view=login");
                exit();
            } else {
                require_once $view;
            }
        }
    }

    ?>
    <script src="./js/formValidation.js"></script>

</body>

</html>