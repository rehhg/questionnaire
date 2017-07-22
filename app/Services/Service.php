<?php

namespace App\Services;

use App\Core\Db;

/**
 * Base Service
 */
abstract class Service {
    
    protected $db;
    
    public function __construct() {
        return $this->db = Db::getInstance();
    }
    
}
