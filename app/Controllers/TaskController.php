<?php

namespace App\Controllers;

use App\Services\TaskService;
use App\Services\UserService;
use App\Core\Auth;
use App\Models\Task;

class TaskController extends BaseController {
    
    private $userService;
    private $auth;
    
    public function __construct() {
        $this->service = new TaskService('dev');
        $this->userService = new UserService('dev');
        $this->auth = new Auth();
    }
    
    /**
     * @template "Task/taskslist.twig"
     * @method "GET"
     */
    public function listAction() {
        $this->auth->checkIfUserLogIn();
        
        $userRole = $this->auth->userRole();
        
        return [
            'tasks' => $this->service->getAllTasks(),
            'role' => $userRole
        ];
    }
    
    /**
     * @template "Task/create.twig"
     * @method ["GET", "POST"]
     */
    public function createAction() {
        $userRole = $this->auth->restrictRightsEmployee();
        
        // get all users for assign
        $users = $this->service->getAllUsersForAssign();
        
        if (!empty($_POST)) {
            $task = new Task($_POST);
            $errors = $this->validate($task);

            if(empty($errors)){
                return [
                    'task' => $this->service->createTask($task),
                    'users' => $users,
                    'role' => $userRole
                ];
            } else {
                return [
                    'errors' => $errors,
                    'users' => $users,
                    'role' => $userRole
                ];
            }
        }
        
        return [
            'users' => $users,
            'role' => $userRole
        ];
    }
    
    /**
     * @template "Task/update.twig"
     * @method ["GET", "POST"]
     */
    public function updateAction($id) {
        $userRole = $this->auth->restrictRightsEmployee();
        
        // get all users for assign
        $users = $this->service->getAllUsersForAssign();
        
        $idTask = intval($id);
        $taskToUpdate = $this->service->getTask($idTask);
        
        if ($taskToUpdate && !empty($_POST)) {
            foreach($_POST as $key => $value) {
                $taskToUpdate->$key = $value;
            }
            
            $errors = $this->validate($taskToUpdate);
            
            if(empty($errors)) {
                return [
                    'task' => $this->service->updateTask($taskToUpdate),
                    'users' => $users,
                    'role' => $userRole
                ];
            } else {
                $taskToUpdate->errors = $errors;
            }
        }
        
        return [
            'task' => $taskToUpdate,
            'users' => $users,
            'role' => $userRole
        ];
    }
    
    /**
     * @method "GET"
     */
    public function deleteAction($id) {
        $this->auth->restrictRightsEmployee();
        
        $idTask = intval($id);
        $errors = [];
        $taskToDelete = $this->service->getTask($idTask);
 
        !filter_var($idTask, FILTER_VALIDATE_INT) ? $errors[] = "id need to be an integer" : true;
        !$taskToDelete ? $errors[] = "There are no task with id = $idTask" : true;

        if(empty($errors)){
            $deletedTask = $this->service->deleteTask($taskToDelete);

            if (is_a($deletedTask, "App\Models\Task")){
                $this->redirect("/taskslist");
                return $deletedTask;
            }
        }
        
        return $errors;
    }
    
    /**
     * @template "Task/taskslist.twig"
     * @method "GET"
     */
    public function statusAction($val, $id) {
        $this->service->changeStatus($val, $id) ? $this->redirect("/taskslist") : false;
    }
    
}
