<?php

namespace App\Core;

class Db extends \PDO {
    
    private static $instance;
    
    private static $pathToConfigYml = 'app/Config/config.yml';
    
    /*
     * Returns DB instance or create initial connection
     * 
     * @param
     * @return $instance;
     */
    private static function getInstance() {
        
        $data = self::getDataFromYaml(self::$pathToConfigYml);
           
        if (!self::$instance) {
            self::$instance = new \PDO($data["db_host"], $data["db_user"], $data["db_pass"]);
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
       
        return self::$instance;
   
    }
    
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
