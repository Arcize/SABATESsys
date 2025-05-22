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

        if ($user && $this->sessionModel->verifyPassword($password, $user['password'])) {
            $this->sessionModel->startSession($user);

            error_log("Inicio de sesión exitoso para el usuario: " . $cedula);

            header("Location: index.php?view=dashboard");
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
