<?php
require_once("app/models/sessionModel.php");
class SessionController  {
    private $sessionModel;

    public function __construct() {
        $this->sessionModel = new SessionModel();
    }

    public function login($username, $password) {
        $user = $this->sessionModel->getUser($username);
        
        if (!$user) {
            error_log("Usuario no encontrado: " . $username);
        }
        
        if ($user && $this->sessionModel->verifyPassword($password, $user['password'])) {
            $this->sessionModel->startSession($user);
            
            error_log("Inicio de sesión exitoso para el usuario: " . $username);
            
            header("Location: index.php?view=dashboard");
            exit();
        } else {
            $_SESSION['login_failed'] = "Credenciales incorrectas. Por favor intente de nuevo.";
            
            error_log("Fallo en el inicio de sesión para el usuario: " . $username);
            
            header("Location: index.php");
            exit();
        }
    }
    

    public function isLoggedIn() {
        return $this->sessionModel->isSessionActive();
    }

    public function logout() {
        $this->sessionModel->destroySession();
        header("Location: index.php?view=login");
        exit();
    }
}

?>