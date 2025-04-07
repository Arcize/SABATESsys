<?php

namespace app\controllers;

use app\models\UserModel;

class UserController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
    }
    public function registerUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            if ($username && $password && $id) {
                if ($this->userModel->isAnEmployee($id)) {
                    $hashedPassword = $this->userModel->passwordHash($password);
                    $this->userModel->setData($username, $hashedPassword);
                    $this->userModel->isAnAdmin($id);
                    $this->userModel->register();
                    $this->userModel->updatePersonIdUser($id);
                    exit();
                } else {
                    $_SESSION['register_failed'] = "No es un empleado";
                    header("Location: index.php?view=register");
                }
            } else {
                $_SESSION['register_failed'] = "Rellene todos los campos";
                echo "Input no válido";
                header("Location: index.php?view=register");
            }
        }
    }
}
