<?php
if (session_status() == PHP_SESSION_NONE) {
    session_name("Tarea");
    session_start();
}

?>