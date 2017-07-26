<?php

namespace App\Services;
use App\Models\User;

class UserService extends Service {
    
    public function createUser(User $user = null) {
        $query = $this->db->prepare("INSERT INTO users VALUES (NULL, ?, ?, NOW())");
        $query->bindParam(1, $user->data["name"], \PDO::PARAM_STR);
        $query->bindParam(2, $user->data["email"], \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new \PDOException($this->getDbError($query));
        }
        
        return $user;
    }
    
    public function getUser($id) {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $query->bindParam(1, $id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new \PDOException($this->getDbError($query));
        }
        
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }
    
    public function updateUser(User $user = null) {
        $query = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $query->bindParam(1, $user->data['name'], \PDO::PARAM_STR);
        $query->bindParam(2, $user->data['email'], \PDO::PARAM_STR);
        $query->bindParam(3, $user->data['id'], \PDO::PARAM_INT);
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
    
}