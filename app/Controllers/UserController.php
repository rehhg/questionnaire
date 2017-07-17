<?php

namespace App\Controllers;

use DocBlockReader\Reader;
use App\Core\App;

class UserController {
    
    /**
     * @method "POST"
     */
    public function createAction() {
        $reader = new \DocBlockReader\Reader('\\App\\Controllers\\UserController', 'createAction');
        $method = $reader->getParameter("method") . "<br />";
        
        if($_SERVER['REQUEST_METHOD'] != $method) {
            header("HTTP/1.0 405 Method Not Allowed");
            include_once App::getRootPath() . '/app/Config/405.php';
            die();
        }
        
        echo 'This is UserController, Create method';
    }
    
    /**
     * @method "GET"
     */
    public function getAction($id) {
        $reader = new \DocBlockReader\Reader('\\App\\Controllers\\UserController', 'getAction');
        $method = $reader->getParameter("method");
        
        if($_SERVER['REQUEST_METHOD'] != $method) {
            header("HTTP/1.0 405 Method Not Allowed");
            include_once App::getRootPath() . '/app/Config/405.php';
            die();
        }
        
        echo "This is UserController, Get method with id = $id";
    }
    
    /**
     * @method "GET/POST"
     */
    public function filterAction($email, $role) {
        $reader = new \DocBlockReader\Reader('\\App\\Controllers\\UserController', 'filterAction');
        $method = $reader->getParameter("method");
        $method = explode("/", $method);
        
        if(!in_array($_SERVER['REQUEST_METHOD'], $method)) {
            header("HTTP/1.0 405 Method Not Allowed");
            include_once App::getRootPath() . '/app/Config/405.php';
            die();
        }
        
        echo "E-mail is $email <br />Role is $role";
    }
}
