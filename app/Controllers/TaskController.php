<?php

namespace App\Controllers;

use App\Services\TaskService;

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
        
    }
    
    /**
     * @template "Task/update.twig"
     * @method ["GET", "POST"]
     */
    public function updateAction() {
        
    }
    
    
    
}
