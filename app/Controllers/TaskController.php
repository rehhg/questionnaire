<?php

namespace App\Controllers;

use App\Services\TaskService;
use App\Models\Task;

class TaskController extends BaseController {
    
    public function __construct() {
        $this->service = new TaskService('dev');
    }
    
    /**
     * @template "Task/taskslist.twig"
     * @method "GET"
     */
    public function listAction() {
        return $this->service->getAllTasks();
    }
    
    /**
     * @template "Task/create.twig"
     * @method ["GET", "POST"]
     */
    public function createAction() {
        
        // get all users for assign
        $this->service->getAllUsersForAssign();

        if (!empty($_POST)) {
            $task = new Task($_POST);
            $errors = $this->validate($task);

            if(empty($errors)){
                return $this->service->createTask($task);
            } else {
                return $errors;
            }
        }
    }
    
    /**
     * @template "Task/update.twig"
     * @method ["GET", "POST"]
     */
    public function updateAction($id) {
        
        // get all users for assign
        $this->service->getAllUsersForAssign();
        
        $idTask = intval($id);
        $taskToUpdate = $this->service->getTask($idTask);

        if ($taskToUpdate && !empty($_POST)) {
            foreach($_POST as $key => $value) {
                $taskToUpdate->$key = $value;
            }
            
            $errors = $this->validate($taskToUpdate);

            if(empty($errors)) {
                return $this->service->updateTask($taskToUpdate);
            } else {
                $taskToUpdate->errors = $errors;
            }
        }

        return $taskToUpdate;
    }
    
    /**
     * @method "GET"
     */
    public function deleteAction($id) {
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
