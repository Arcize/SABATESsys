<?php
namespace app\models;
use app\models\UserModel;

class SessionModel
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getUser($cedula)
    {
        return $this->userModel->getUserByCedula($cedula);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function startSession($user)
    {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['role'] = $user['id_rol'];
    }

    public function destroySession()
    {
        session_start();
        session_destroy();
    }

    public function isSessionActive()
    {
        return isset($_SESSION['id_usuario']);
    }
}
