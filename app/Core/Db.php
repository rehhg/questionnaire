<?php

namespace App\Core;

class Db {
    
    private static $instance;
    
    private static $pathToConfigYml = 'app/Config/config.yml';
    
    /*
     * Returns DB instance or create initial connection
     * 
     * @param
     * @return $instance;
     */
    public static function getInstance($env = 'dev') {
        
        $config = self::getDataFromYaml(self::$pathToConfigYml);
           
        if (!self::$instance) {
            self::$instance = new \PDO($config[$env]["host"], $config[$env]["user"], $config[$env]["pass"]);
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
       
        return self::$instance;
   
    }
    
    private function __clone() {}    
    private function __construct() {}
    private function __sleep() {}    
    private function __wakeup() {}
    
    /*
     * Returns data from .yml files
     * 
     * @return $parsed;
     */
    private static function getDataFromYaml($path) {
           
        $yaml = \App\Core\App::getRootPath() . '/' . $path;
        $parsed = yaml_parse_file($yaml);
        
        return $parsed;
        
    }
    
}
