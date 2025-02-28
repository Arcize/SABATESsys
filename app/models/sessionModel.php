<?php
require_once("app/models/userModel.php");
class SessionModel
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new userModel();
    }

    public function getUser($username)
    {
        return $this->userModel->getUserByUsername($username);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function startSession($user)
    {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['username'] = $user['username'];
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
