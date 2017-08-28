<?php

namespace App\Controllers;

use App\Models\User;
use App\Validators\UserValidator;
use App\Services\UserService;

class UserController extends BaseController {

    public function __construct() {
        $this->service = new UserService('dev');
    }
    
    /**
     * @template "User/auth.twig"
     * @method ["GET", "POST"]
     */
    public function authAction() {
        if(isset($_POST['login'])) {
            $user = new User($_POST);
            $errors = UserValidator::validateAuth($user);
            
            $userData = $this->userService->checkIfUserExist($user);
            
            if(!$userData) {
                $errors[] = 'There are no user with this username and password';
            } 
            
            if(empty($errors)) {
                $this->userService->auth($userData);
                header('Location: /');
            }
            
            return $errors;
        }
    }
    
    /**
     * @method ["GET", "POST"]
     */
    public function logoutAction() {
        setcookie('user', null, -1);
        header("Location: /");
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
        $errors = [];
        $userToDelete = $this->service->getUser($idUser);
        
        !filter_var($idUser, FILTER_VALIDATE_INT) ? $errors[] = "id need to be an integer" : true;
        !$userToDelete ? $errors[] = "There are no user with id = $idUser" : true;

        if(empty($errors)){
            $deletedUser = $this->service->deleteUser($userToDelete);

            if (is_a($deletedUser, "App\Models\User")){
                $this->redirect("/user/userslist");
                return $deletedUser;
            }
        }
        
        return $errors;
    }

}
