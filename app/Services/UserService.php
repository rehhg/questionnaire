<?php

namespace App\Services;

use App\Models\User;
use PDOException;

class UserService extends Service {
    
    public function createUser(User $user = null) {
        $query = $this->db->prepare("INSERT INTO users VALUES (NULL, ?, ?, NOW())");
        $query->bindParam(1, $user->name, \PDO::PARAM_STR);
        $query->bindParam(2, $user->email, \PDO::PARAM_STR);
        
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        } else {
            $query = $this->db->prepare("SELECT id, putdate FROM users WHERE name = ? AND email = ?");
            $query->bindParam(1, $user->name, \PDO::PARAM_STR);
            $query->bindParam(2, $user->email, \PDO::PARAM_STR);
            if(!$query->execute()) {
                throw new PDOException($this->getDbError($query));
            }
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            
            $user->id = $result["id"];
            $user->putdate = $result["putdate"];
        }
        
        return $user;
    }
    
    public function getUser($id) {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $query->bindParam(1, $id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }
    
    public function updateUser(User $user = null) {
        $query = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $query->bindParam(1, $user->name, \PDO::PARAM_STR);
        $query->bindParam(2, $user->email, \PDO::PARAM_STR);
        $query->bindParam(3, $user->id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        return $user;
        
    }
    
    public function deleteUser($id) {
        if(gettype($id) === 'integer'){
            $query = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $query->bindParam(1, $id, \PDO::PARAM_INT);
            $query->execute();
            if($query->rowCount() == 0) {
                throw new PDOException("There are no user with id = $id");
            }
        } else {
            throw new PDOException('id need to be an integer');
        }
        
        return true;
        
    }
    
}