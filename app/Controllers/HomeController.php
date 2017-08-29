<?php

namespace App\Controllers;

use App\Services\UserService;

class HomeController extends BaseController {
    
    private $userService;
    
    public function __construct() {
        $this->userService = new UserService('dev');
    }
    
    /**
     * @template "index.twig"
     * @method ["GET"]
     */
    public function indexAction() {
        !isset($_COOKIE['user']) ? $this->redirect('/login') : true;
        
        $userRole = $this->userService->identifyUser()->data[0]['user_role'];

        return [
            'role' => $userRole
        ];
    }
    
}