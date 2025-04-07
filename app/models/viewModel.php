<?php
namespace app\models;

class ViewModel {
    protected function getViewModel($vista) {
        // Incluir el archivo de la lista blanca y obtener la lista
        $whitelist = include __DIR__ . '/../config/whitelist.php';

        $viewPath = '../app/views/' . $vista . "-view.php"; // Ruta relativa desde public/index.php
        $notFoundPath = '../app/views/404-view.php'; // Ruta relativa desde public/index.php

        if (in_array($vista, $whitelist)) {
            if (is_file(__DIR__ . '/../views/' . $vista . "-view.php")) { // Verifica si el archivo existe (ruta absoluta)
                $content = $viewPath; // Devuelve la ruta relativa
            } else {
                $content = $notFoundPath; // Devuelve la ruta relativa
            }
        } else {
            $content = $notFoundPath; // Devuelve la ruta relativa
        }

        return $content;
    }
}