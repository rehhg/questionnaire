<?php

namespace App\Core;

use App\Services\UserService;
use App\Config\Exception404;

class Auth {
    
    private $service;
    
    public function __construct() {
        $this->service = new UserService('dev');
    }
    
    public function userRole() {
        return $this->service->identifyUser()->data[0]['user_role'];
    }
    
    public function checkIfUserLogIn() {
        return !isset($_COOKIE['user']) ? $this->redirect('/login') : true;
    }
    
    public function restrictRightsEmployee() {
        $this->checkIfUserLogIn();
        
        if($this->userRole() == 'Employee') {
            throw new Exception404();
        }
        
        return $this->userRole();
    }
    
    public function restrictRightsEmployeeManager() {
        $this->checkIfUserLogIn();
        
        if($this->userRole() == 'Employee' || $this->userRole() == 'Manager') {
            throw new Exception404();
        }
        
        return $this->userRole();
    }
    
    private function redirect($location) {
        header("Location: $location");
    }
    
}
