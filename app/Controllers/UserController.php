<?php

namespace App\Controllers;

use DocBlockReader\Reader;
use App\Core\Router;

class UserController {
    
    /**
     * @method "POST"
     */
    public function createAction() {
        Router::annotationReader('\\App\\Controllers\\UserController', 'createAction');
        
        echo 'This is UserController, Create method';
    }
    
    /**
     * @method "GET"
     */
    public function getAction($id) {
        Router::annotationReader('\\App\\Controllers\\UserController', 'getAction');
        
        echo "This is UserController, Get method with id = $id";
    }
    
    /**
     * @method "GET"
     * @method "POST"
     */
    public function filterAction($email, $role) {
        Router::annotationReader('\\App\\Controllers\\UserController', 'filterAction');
        
        echo "E-mail is $email <br />Role is $role";
    }
}
