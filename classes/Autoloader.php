<?php
class Autoloader {
    static public function register()
    {
        spl_autoload_register([self::class, "autoload"]);
    }
    static public function autoload($class_name)
    {
        require "classes/" . $class_name . ".php";
    }
}