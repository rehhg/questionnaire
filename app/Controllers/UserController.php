<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;

class UserController extends BaseController {

    private $service;

    public function __construct() {
        $this->service = new UserService('dev');
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
        return $this->service->getAllUsers();
    }

    /**
     * @template "User/create.twig"
     * @method ["GET", "POST"]
     */
    public function createAction() {
        if (!empty($_POST)) {
            $user = new User($_POST);
            $errors = $this->validate($user);
                    
            if(empty($errors)){
                return $this->service->createUser($user);
            } else {
                return $errors;
            }
        }
    }

    /**
     * @template "User/update.twig"
     * @method ["GET", "POST"]
     */
    public function updateAction($id) {
        $idUser = intval($id);
        $userToUpdate = $this->service->getUser($idUser);
        
        if ($userToUpdate && !empty($_POST)) {
            foreach($_POST as $key => $value) {
                $userToUpdate->$key = $value;
            }
            
            if($_POST['password'] !== $_POST['confirm_password']) {
                $userToUpdate->password = sha1($_POST['password']);
                $userToUpdate->confirm_password = sha1($_POST['confirm_password']);
            }

            $errors = $this->validate($userToUpdate);

            if(empty($errors)) {
                return $this->service->updateUser($userToUpdate);
            } else {
                $userToUpdate->errors = $errors;
            }
        }

        return $userToUpdate;
    }

    /**
     * @method "GET"
     */
    public function deleteAction($id) {
        $idUser = intval($id);

        $deletedUser = $this->service->deleteUser($idUser);

        if ($deletedUser) {
            $this->redirect("/user/userslist");
        }

        return $deletedUser;
    }

}
