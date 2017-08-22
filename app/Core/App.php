<?php

namespace App\Core;

/**
 * Returns dirname
 */
class App {
    
    public static function getRootPath() {
        return (dirname(dirname(__DIR__)));
    }
    
    public static function clean($var) {
        return trim(strip_tags(stripslashes(htmlspecialchars($var))));
    }
    
}
