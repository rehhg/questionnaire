<?php

namespace App\Controllers;

use App\Core\App;
use App\Models\User;
use App\Services\UserService;

class UserController {

    private $userService;
    private $user;

    public function __construct() {
        $this->userService = new UserService('dev');
        $this->user = new User();
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
            $errors = array();
            
            !empty($_POST['firstname']) ? 
                $this->user->firstname = App::clean($_POST['firstname']) : $errors[] = 'Please enter firstname';
            !empty($_POST['lastname']) ? 
                $this->user->lastname = App::clean($_POST['lastname']) : $errors[] = 'Please enter lastname';
            !empty($_POST['email']) ? 
                $this->user->email = App::clean($_POST['email']) : $errors[] = 'Please enter email';
            !empty($_POST['username']) ? 
                $this->user->username = App::clean($_POST['username']) : $errors[] = 'Please enter username';
            !empty($_POST['user_role']) ? 
                $this->user->user_role = App::clean($_POST['user_role']) : $errors[] = 'Please enter user_role';

            if ($_POST['password'] === $_POST['confirm_password'] 
                    && isset($_POST['password']) && isset($_POST['confirm_password'])) {
                $this->user->password = sha1(App::clean($_POST['password']));
            } else {
                $errors[] = "Passwords didn't match";
            }

            if(empty($errors)){
                $newUser = $this->userService->createUser($this->user);
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
            $userToUpdate->firstname = App::clean($_POST['firstname']);
            $userToUpdate->lastname = App::clean($_POST['lastname']);
            $userToUpdate->email = App::clean($_POST['email']);
            $userToUpdate->username = App::clean($_POST['username']);
            $userToUpdate->user_role = App::clean($_POST['user_role']);

            if ($userToUpdate->password !== $_POST['password']) {
                $userToUpdate->password = sha1(App::clean($_POST['password']));
            } else {
                $userToUpdate->password = App::clean($_POST['password']);
            }

            $updateUser = $this->userService->updateUser($userToUpdate);

            return $updateUser;
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
