<?php

namespace App\Controllers;

class UserController {
    
    /**
     * @template "app/Views/User/create.twig"
     * @method "POST"
     */
    public function createAction() {
        return [
            
        ];
    }
    
    /**
     * @template "app/Views/User/get.twig"
     * @method "GET"
     */
    public function getAction($id) {
        return [
            "id" => $id
        ];
    }
    
    /**
     * @template "app/Views/User/filter.twig"
     * @method ["GET", "POST"]
     */
    public function filterAction($email, $role) {
        return [
            "email" => $email,
            "role" => $role
        ];
    }
}
