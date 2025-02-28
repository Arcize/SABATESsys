<?php

// Autoload de clases
spl_autoload_register(function ($class_name) {
    include 'controllers/' . $class_name . '.php';
});

// Iniciar sesión
session_start();

// Configuraciones de base de datos
require_once('app/models/DB.php');

// Controladores necesarios
require_once('controllers/SessionController.php');
require_once('controllers/UserController.php');
require_once('controllers/EmployeeController.php');
require_once('controllers/ViewController.php');
require_once('controllers/pcController.php');

?>