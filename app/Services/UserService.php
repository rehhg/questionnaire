<?php

namespace App\Services;

class UserService extends Service {
    
    public function createUser(\User $user = null) {
        $query = $this->db->prepare("INSERT INTO users VALUES (NULL, ?, ?, NOW())");
        $query->bindParam(1, $user->username, \PDO::PARAM_STR);
        $query->bindParam(2, $user->email, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new \PDOException($query->errorInfo);
        }
        
        return $user;
    }
    
    public function getUser($id, \User $user = null) {
        $user = $this->db->query("SELECT * FROM users WHERE id = $id")->fetch(\PDO::FETCH_OBJ);
        
        return $user;
    }
    
    public function updateUser($id, \User $user = null) {
        $query = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = $id");
        $query->bindParam(1, $user->username, \PDO::PARAM_STR);
        $query->bindParam(2, $user->email, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new \PDOException($query->errorInfo);
        }
        
        return $user;
        
    }
    
    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = $id");
        
        return true;
        
    }
    
}
