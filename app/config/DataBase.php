<?php
namespace app\config;
require_once("config.php");

/**
 * Clase DataBase.
 * 
 * Esta clase implementa el patrón Singleton para gestionar la conexión a la base de datos.
 */
class DataBase {
    protected static $instance;

    private function __construct() {}

    /**
     * Obtiene la instancia única de la conexión a la base de datos.
     * 
     * @return PDO La instancia de la conexión a la base de datos.
     * @throws Exception Si ocurre un error al conectarse al servidor de BD.
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            try {
                $dsn = DB_MANAGER . ":host=" . DB_HOST . ";dbname=" . DB_NAME;
                self::$instance = new \PDO($dsn, DB_USER, DB_PASS);
                self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                throw new \Exception('Error al conectarse al servidor de BD', 0);
            }
        }
        return self::$instance;
    }

    private function __clone() {}
}