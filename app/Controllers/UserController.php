<?php

namespace App\Controllers;

class UserController {
    
    /**
     * @template "User/create.twig"
     * @method "POST"
     */
    public function createAction() {
        return [
            
        ];
    }
    
    /**
     * @template "User/get.twig"
     * @method "GET"
     */
    public function getAction($id) {
        return [
            "id" => $id
        ];
    }
    
    /**
     * @template "User/filter.twig"
     * @method ["GET", "POST"]
     */
    public function filterAction($email, $role) {
        return [
            "email" => $email,
            "role" => $role
        ];
    }
}
