<?php
class viewModel {
    protected function getViewModel($vista) {
        // Incluir el archivo de la lista blanca y obtener la lista
        $whitelist = include("app/models/whitelist.php");

        if (in_array($vista, $whitelist)) {
            if (is_file("app/views/content/" . $vista . "-view.php")) {
                $content = "app/views/content/" . $vista . "-view.php";
            } else {
                $content = "app/views/content/404-view.php";
            }
        } else {
            $content = "app/views/content/404-view.php";
        }
        
        return $content;
    }
}
?>
