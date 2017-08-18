<?php

namespace App\Services;

use App\Models\Task;

class TaskService extends Service {
    
    public function getAllTasks() {
        $query = $this->db->prepare("SELECT * FROM tasks");
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        $tasks = [];
        
        foreach($data as $value) {
            $task = new Task($value);
            $tasks[] = $task;
        }

        return $tasks;
    }
    
}
