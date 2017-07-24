<?php

namespace App\Services;

use App\Core\Db;

/**
 * Base Service
 */
abstract class Service {

    protected $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    protected function getDbError($query) {
        return $query->errorInfo[2];
    }

}
