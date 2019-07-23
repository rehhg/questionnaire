<?php

namespace App\Services;

use App\Core\Db;

/**
 * Base Service
 */
abstract class Service {

    protected $db;
    protected $env = 'dev';

    public function __construct($ut) {
        $this->db = Db::getInstance($ut ? $ut : $this->env);
    }
    
    protected function getDbError($query) {
        return $query->errorInfo[2];
    }

}
