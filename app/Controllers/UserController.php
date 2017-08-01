<?php

namespace App\Controllers;

use Exception;
use App\Core\App;
use App\Models\User;
use App\Services\UserService;

class UserController {
    
    /**
     * @template "User/create.twig"
     * @method ["GET", "POST"]
     */
    public function createAction() {
        $userService = new UserService('dev');
        $User = new User();
        
        if(isset($_POST['create']) && !empty($_POST)) {
            $User->firstname = App::clean($_POST['firstname']);
            $User->lastname = App::clean($_POST['lastname']);
            $User->email = App::clean($_POST['email']);
            $User->username = App::clean($_POST['username']);
            $User->user_role = App::clean($_POST['user_role']);
            
            if($_POST['password'] === $_POST['confirm_password']){
                $User->password = sha1(App::clean($_POST['password']));
            } else {
                throw Exception('Passwords did not match');
            }
            
            $newUser = $userService->createUser($User);
            
            return $newUser;
        }
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
    
    /**
     * @template "User/userslist.twig"
     * @method "GET"
     */
    public function listAction() {
        $userService = new UserService('dev');
        $allUsers = $userService->getAllUsers();
        
        return $allUsers;
    }
    
    /**
     * @template "User/update.twig"
     * @method ["GET", "POST"]
     */
    public function updateAction($id) {
        $id_user = intval($id);
        $userService = new UserService('dev');
        $userToUpdate = $userService->getUser($id_user);
        
        if(isset($_POST['update']) && $userToUpdate && !empty($_POST)){
            $userToUpdate->firstname = App::clean($_POST['firstname']);
            $userToUpdate->lastname = App::clean($_POST['lastname']);
            $userToUpdate->email = App::clean($_POST['email']);
            $userToUpdate->username = App::clean($_POST['username']);
            $userToUpdate->user_role = App::clean($_POST['user_role']);
            
            if($userToUpdate->password !== $_POST['password']){
                $userToUpdate->password = sha1(App::clean($_POST['password']));
            } else {
                $userToUpdate->password = App::clean($_POST['password']);
            }
            
            $updateUser = $userService->updateUser($userToUpdate);
            
            return $updateUser;
        }
        
        return $userToUpdate;
    }
 }
