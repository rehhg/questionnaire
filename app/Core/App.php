<?php

namespace App\Core;

/**
 * Returns dirname
 */
class App {
    
    public static function getRootPath() {
        return (dirname(dirname(__DIR__)));
    }
    
}
