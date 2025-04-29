<?php

namespace app\helpers;

use app\controllers\UserController;

class PermissionHelper
{
    public static function getNavbarPermissions()
    {
        $userController = new UserController();
        $permissions = [
            'puede_ver_consultar' => false,
            'puede_ver_dashboard' => false,
            'puede_ver_empleados' => false,
            'puede_editar_empleados' => false,
            'puede_registrar_empleados' => false,
            'puede_ver_equipos' => false,
            'puede_editar_equipos' => false,
            'puede_registrar_equipos' => false,
            'puede_reportar_falla' => false,
            'puede_ver_falla' => false,
            'puede_reportar_actividad' => false,
            'puede_ver_actividad' => false,
            'puede_editar_actividad' => false,
            'puede_ver_usuarios' => false,
            'puede_ver_inventario' => false,
            'puede_hacer_regis_inventario' => false,
            'puede_editar_regis_inventario' => false,
            'puede_ver_notificaciones' => false,
            'puede_cargar_regis_empleados' => false,
            'puede_recup_contraseña' => false,
            'puede_ver_todos_repor_falla' => false,
            'puede_ver_todos_repor_actividades' => false,
            'puede_ver_configuracion' => false,
        ];

        if (isset($_SESSION['id_usuario'])) {
            $userId = $_SESSION['id_usuario'];
            $permissions['puede_ver_consultar'] = $userController->hasPermission("ver_consultar", $userId);
            $permissions['puede_ver_dashboard'] = $userController->hasPermission("ver_dashboard", $userId);
            $permissions['puede_ver_empleados'] = $userController->hasPermission("ver_empleados", $userId);
            $permissions['puede_editar_empleados'] = $userController->hasPermission("editar_empleados", $userId);
            $permissions['puede_registrar_empleados'] = $userController->hasPermission("registrar_empleados", $userId);
            $permissions['puede_ver_equipos'] = $userController->hasPermission("ver_equipos", $userId);
            $permissions['puede_editar_equipos'] = $userController->hasPermission("editar_equipos", $userId);
            $permissions['puede_registrar_equipos'] = $userController->hasPermission("registrar_equipos", $userId);
            $permissions['puede_reportar_falla'] = $userController->hasPermission("reportar_falla", $userId);
            $permissions['puede_ver_falla'] = $userController->hasPermission("ver_falla", $userId);
            $permissions['puede_reportar_actividad'] = $userController->hasPermission("reportar_actividad", $userId);
            $permissions['puede_ver_actividad'] = $userController->hasPermission("ver_actividad", $userId);
            $permissions['puede_editar_actividad'] = $userController->hasPermission("editar_actividad", $userId);
            $permissions['puede_ver_usuarios'] = $userController->hasPermission("ver_usuarios", $userId);
            $permissions['puede_ver_inventario'] = $userController->hasPermission("ver_inventario", $userId);
            $permissions['puede_hacer_regis_inventario'] = $userController->hasPermission("hacer_regis_inventario", $userId);
            $permissions['puede_editar_regis_inventario'] = $userController->hasPermission("editar_regis_inventario", $userId);
            $permissions['puede_ver_notificaciones'] = $userController->hasPermission("ver_notificaciones", $userId);
            $permissions['puede_cargar_regis_empleados'] = $userController->hasPermission("cargar_regis_empleados", $userId);
            $permissions['puede_recup_contraseña'] = $userController->hasPermission("recup_contraseña", $userId);
            $permissions['puede_ver_todos_repor_falla'] = $userController->hasPermission("ver_todos_repor_falla", $userId);
            $permissions['puede_ver_todos_repor_actividades'] = $userController->hasPermission("ver_todos_repor_actividades", $userId);
            $permissions['puede_ver_configuracion'] = $userController->hasPermission("ver_configuracion", $userId);
        }

        return $permissions;
    }
}