<?php
class Autoloader {
    /**
     * Enregistrement de l'autoloader
     *
     * @return void
     */
    static public function register()
    {
        spl_autoload_register([self::class, "autoload"]);
    }
    /**
     * Autoloader
     *
     * @param string $className Nom de la classe à load
     *
     * @return void
     */
    static public function autoload($className)
    {
        require "classes/" . $className . ".php";
    }
}