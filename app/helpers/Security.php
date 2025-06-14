<?php

namespace app\helpers;

use app\controllers\SessionController;
use app\controllers\UserController;

class Security
{
    private static $protectedViews = [
        'dashboard',
        'config',
        'employeeTable',
        'pcTable',
        'userTable',
        'faultReportTable',
        'myFaultReports'
        // ... otras vistas protegidas ...
    ];

    private static $permissionBasedViews = [
        'dashboard' => 'ver_dashboard',
        'employeeTable' => 'ver_empleados',
        'pcTable' => 'ver_equipos',
        'faultReportTable' => 'ver_todos_repor_falla',
        'userTable' => 'ver_usuarios',
        'config' => 'ver_configuracion',
        'myFaultReports' => 'ver_falla',
        // ... otras vistas que requieren permisos específicos ...
    ];

    public static function isProtectedView(string $view): bool
    {
        return in_array($view, self::$protectedViews);
    }

    public static function requiresPermission(string $view): ?string
    {
        return self::$permissionBasedViews[$view] ?? null;
    }

    public static function enforceAuthentication()
    {
        $SessionController = new SessionController();
        if (!$SessionController->isLoggedIn()) {
            header("Location: index.php?view=login");
            exit();
        }
    }

    public static function enforcePermission(string $permission)
    {
        $userController = new UserController();
        if (!($userController->hasPermission($permission, $_SESSION['id_usuario'] ?? null))) {
            header("HTTP/1.1 403 Forbidden");
            echo "Acceso denegado.";
            exit();
        }
    }

    public static function checkViewAccess(string $view)
    {
        if (self::isProtectedView($view)) {
            self::enforceAuthentication();
            $requiredPermission = self::requiresPermission($view);
            if ($requiredPermission !== null) {
                self::enforcePermission($requiredPermission);
            }
        }
    }
}