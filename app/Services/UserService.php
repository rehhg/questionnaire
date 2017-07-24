<?php

namespace App\Services;

class UserService extends Service {
    
    public function createUser(\User $user = null) {
        $query = $this->db->prepare("INSERT INTO users VALUES (NULL, ?, ?, NOW())");
        $query->bindParam(1, $user->username, \PDO::PARAM_STR);
        $query->bindParam(2, $user->email, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new \PDOException($this->getDbError($query));
        }
        
        return $user;
    }
    
    public function getUser(\User $user = null) {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $query->bindParam(1, $user->id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new \PDOException($this->getDbError($query));
        }
        
        $query->fetchAll(\PDO::FETCH_ASSOC);
        
        return new \User($query);
    }
    
    public function updateUser(\User $user = null) {
        $query = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = $user->id");
        $query->bindParam(1, $user->username, \PDO::PARAM_STR);
        $query->bindParam(2, $user->email, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new \PDOException($this->getDbError($query));
        }
        
        return $user;
        
    }
    
    public function deleteUser(\User $user = null) {
        $query = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $query->bindParam(1, $user->id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new \PDOException($this->getDbError($query));
        }
        
        return $user;
        
    }
    
    protected function getDbError($query) {
        return $query->errorInfo[2];
    }
    
}
