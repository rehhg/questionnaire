<?php

namespace App\Services;

use App\Core\Db;

/**
 * Base Service
 */
abstract class Service {
    
    protected $Db;
    
    public function __construct() {
        return $this->Db = Db::getInstance();
    }
    
}
