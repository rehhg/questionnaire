<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Core\Auth;

class HomeController extends BaseController {
    
    private $userService;
    private $auth;
    
    public function __construct() {
        $this->userService = new UserService('dev');
        $this->auth = new Auth();
        $this->auth->checkIfUserLogIn();
    }
    
    /**
     * @template "index.twig"
     * @method ["GET"]
     */
    public function indexAction() {
        $this->auth->userRole();

        return [
            
        ];
    }
    
}