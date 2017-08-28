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
            
            // get department
            $department = $this->getNameFromTable('department', 'department', 'id_department', $task->id_department);
            $task->department = $department;
            
            // get type
            $type = $this->getNameFromTable('type', 'task_type', 'id_type', $task->id_type);
            $task->tasktype = $type;
            
            $task->description = htmlspecialchars_decode($task->description);
            
            $task->user = $this->getAllUsersFromUsersTasks($task->id_task);
            
            $tasks[] = $task;
        }

        return $tasks;
    }
    
    public function createTask(Task $task = null) {
        
        // get id_department
        $id_department = $this->getIdFromTable('id_department', 'department', 'department', $task->department);

        // get id_type
        $id_type = $this->getIdFromTable('id_type', 'task_type', 'type', $task->tasktype);

        // create task
        $query = $this->db->prepare("INSERT INTO tasks VALUES (NULL, ?, ?, ?, ?, ?)");
        $query->bindParam(1, $task->subject, \PDO::PARAM_STR);
        $query->bindParam(2, $task->description, \PDO::PARAM_STR);
        $query->bindParam(3, $task->created_at, \PDO::PARAM_STR);
        $query->bindParam(4, $id_department, \PDO::PARAM_INT);
        $query->bindParam(5, $id_type, \PDO::PARAM_INT);
        
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        } else {
            $query = $this->db->prepare("SELECT id_task, created_at FROM tasks WHERE subject = ? AND description = ?");
            $query->bindParam(1, $task->subject, \PDO::PARAM_STR);
            $query->bindParam(2, $task->description, \PDO::PARAM_STR);
            if(!$query->execute()) {
                throw new PDOException($this->getDbError($query));
            }
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            
            $task->id_task = $result["id_task"];
            
            $this->insertInformationIntoUsersTask($task->assign, $task->id_task);
        }

        return $task;
    }
    
    public function updateTask(Task $task = null) {
        $query = $this->db->prepare("UPDATE tasks SET subject = ?, description = ?, "
                . "created_at = ?, id_department = ?, id_type = ? WHERE id_task = ?");
        $query->bindParam(1, $task->subject, \PDO::PARAM_STR);
        $query->bindParam(2, $task->description, \PDO::PARAM_STR);
        $query->bindParam(3, $task->created_at, \PDO::PARAM_STR);
        $query->bindParam(4, $task->id_department, \PDO::PARAM_INT);
        $query->bindParam(5, $task->id_type, \PDO::PARAM_INT);
        $query->bindParam(6, $task->id_task, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        return $task;
        
    }
    
    public function deleteTask(Task $task = null) {
        $query = $this->db->prepare("DELETE FROM tasks WHERE id_task = ?");
        $query->bindParam(1, $task->id_task, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }

        return $task;
        
    }
    
    public function getTask($id) {
        $query = $this->db->prepare("SELECT * FROM tasks WHERE id_task = ?");
        $query->bindParam(1, $id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        $data['id_user'] = $this->getIdFromTable('id_user', 'users_tasks', 'id_task', $id);

        return $data ? new Task($data) : null;
    }
    
    public function getAllUsersForAssign() {
        $query = $this->db->prepare("SELECT id_user, firstname, lastname FROM users");
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $_REQUEST = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        return $_REQUEST;
    }
    
    private function getAllUsersFromUsersTasks($id_task) {
        $query = $this->db->prepare("SELECT firstname, lastname FROM users WHERE id_user = (SELECT id_user FROM users_tasks WHERE id_task = ?)");
        $query->bindParam(1, $id_task, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        $query = $this->db->prepare("SELECT status FROM users_tasks WHERE id_task = ?");
        $query->bindParam(1, $id_task, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data['status'] = $query->fetchColumn();
        
        return $data;
    }
    
    public function changeStatus($val, $id_task) {
        $val == 1 ? $status = 'Done' : false;
        $val == 2 ? $status = 'In progress' : false;
        $val == 3 ? $status = 'Open' : false;
        
        $query = $this->db->prepare("UPDATE users_tasks SET status = ? WHERE id_task = ?");
        $query->bindParam(1, $status, \PDO::PARAM_STR);
        $query->bindParam(2, $id_task, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        return true;
    }

    private function insertInformationIntoUsersTask($id_user, $id_task) {
        $query = $this->db->prepare("INSERT INTO users_tasks VALUES (?, ?, 'Open')");
        $query->bindParam(1, $id_user, \PDO::PARAM_INT);
        $query->bindParam(2, $id_task, \PDO::PARAM_INT);
        
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        return true;
    }

    private function getIdFromTable($idName, $table, $colName, $value) {
        $query = $this->db->prepare("SELECT $idName FROM $table WHERE $colName = ?");
        $query->bindParam(1, $value, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $id = $query->fetch(\PDO::FETCH_NUM);

        return $id[0];
    }
    
    private function getNameFromTable($name, $table, $idName, $id) {
        $query = $this->db->prepare("SELECT $name FROM $table WHERE $idName = ?");
        $query->bindParam(1, $id, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $result = $query->fetch(\PDO::FETCH_NUM);

        return $result[0];
    }
    
}
