<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;

class UserController extends BaseController {

    private $userService;

    public function __construct() {
        $this->userService = new UserService('dev');
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
        $allUsers = $this->userService->getAllUsers();

        return $allUsers;
    }

    /**
     * @template "User/create.twig"
     * @method ["GET", "POST"]
     */
    public function createAction() {
        if (isset($_POST['create']) && !empty($_POST)) {
            $user = new User($_POST);
            $errors = $this->validate($user);
                    
            if(empty($errors)){
                $newUser = $this->userService->createUser($user);
                return $newUser;
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
        $id_user = intval($id);
        $userToUpdate = $this->userService->getUser($id_user);
        
        if (isset($_POST['update']) && $userToUpdate && !empty($_POST)) {
            foreach($_POST as $key => $value) {
                $userToUpdate->$key = $value;
            }
            
            if($_POST['password'] !== $_POST['confirm_password']) {
                $userToUpdate->password = sha1($_POST['password']);
                $userToUpdate->confirm_password = sha1($_POST['confirm_password']);
            }

            $errors = $this->validate($userToUpdate);

            if(empty($errors)) {
                $updateUser = $this->userService->updateUser($userToUpdate);
                return $updateUser;
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
        $id_user = intval($id);

        $deletedUser = $this->userService->deleteUser($id_user);

        if ($deletedUser) {
            header("Location: /user/userslist");
        }

        return $deletedUser;
    }

}
