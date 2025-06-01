<?php

namespace app\controllers;

use app\models\SessionModel;

class SessionController
{
    private $sessionModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
    }

    public function login($cedula, $password)
    {
        $user = $this->sessionModel->getUser($cedula);

        if (!$user) {
            error_log("Usuario no encontrado: " . $cedula);
        }

        else if ($user && $user['estado_empleado'] == 'Inactivo') {
            $_SESSION['login_failed'] = "El usuario está inactivo. Por favor contacte al administrador.";

            error_log("Usuario inactivo: " . $cedula);

            header("Location: index.php");
            exit();
        }

        else if ($user && $this->sessionModel->verifyPassword($password, $user['password'])) {
            $this->sessionModel->startSession($user);

            error_log("Inicio de sesión exitoso para el usuario: " . $cedula);

            // Redirección según tipo de usuario
            if (isset($user['id_rol']) && $user['id_rol'] == 1) {
                header("Location: index.php?view=dashboard"); // Admin
            } else {
                header("Location: index.php?view=inicio"); // Usuario estándar
            }
            exit();
        } else {
            $_SESSION['login_failed'] = "Credenciales incorrectas. Por favor intente de nuevo.";

            error_log("Fallo en el inicio de sesión para el usuario: " . $cedula);

            header("Location: index.php");
            exit();
        }
    }


    public function isLoggedIn()
    {
        return $this->sessionModel->isSessionActive();
    }

    public function logout()
    {
        $this->sessionModel->destroySession();
        header("Location: index.php?view=login");
        exit();
    }
}
