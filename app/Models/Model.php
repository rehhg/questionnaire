<?php

namespace App\Models;

/**
 * The main Model
 */
abstract class Model {
    
    public $data;
    
    public function __construct($data = []) { 
        $this->data = $data; 
    }

    public function &__get($name) {
        return $this->data[$name];
        }

    public function __set($name, $value) {
        return $this->data[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __unset($name) {
        unset($this->data[$name]);
    }
    
}
