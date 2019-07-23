<?php

namespace App\Core;

use App\Services\UserService;
use App\Config\Exception404;

class Auth {
    
    private $service;
    private $routes;
    
    const USER_ROLE_ADMIN = 'Admin';
    const USER_ROLE_MANAGER = 'Manager';
    const USER_ROLE_EMPLOYEE = 'Employee';
    
    public function __construct() {
        $this->service = new UserService('dev');
        $routesPath = App::getRootPath() . '/app/Config/routes.php';
        $this->routes = include($routesPath);
    }
    
    public function userRole() {
        $_SESSION['role'] = $this->service->identifyUser()->data[0]['user_role'];
        return $_SESSION['role'];
    }
    
    public function checkIfUserLogIn() {
        return !isset($_COOKIE['user']) ? $this->redirect('/login') : true;
    }
    
    public function restrictRights() {
        $this->checkIfUserLogIn();
        
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $roles = [];
        
        foreach ($this->routes as $uriPattern => $path) {
            if(preg_match("~^$uriPattern$~", $uri)) {
                $roles = $path['roles'];
            }
        }
        
        if(isset($_SESSION['role'])){
            if(!in_array($this->userRole(), $roles)) {
                throw new Exception404();
            }
        }
        
        return $this->userRole();
    }
    
    public function getAllRoles() {
        return [
            1 => self::USER_ROLE_ADMIN,
            2 => self::USER_ROLE_EMPLOYEE,
            3 => self::USER_ROLE_MANAGER,
        ];
    }

    private function redirect($location) {
        header("Location: $location");
    }
    
}
