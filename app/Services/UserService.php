<?php

namespace App\Services;

class UserService extends Service {
    
    public function createUser($data = array()) {
        $db = $this->Db;
        
        $username = $data['username'];
        $email = $data['email'];
        
        $query = $db->prepare("INSERT INTO users VALUES (NULL, ?, ?, NOW())");
        $query->bindParam(1, $username, \PDO::PARAM_STR);
        $query->bindParam(2, $email, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new Exception("Something wrong with your query");
        }
        
        return true;
    }
    
    public function getUser($id) {
        $db = $this->Db;
        
        $user = $db->query("SELECT * FROM users WHERE id = $id")->fetch(\PDO::FETCH_ASSOC);
        
        return $user["name"];
    }
    
    public function updateUser($id, $data) {
        $db = $this->Db;
        
        $username = $data['username'];
        $email = $data['email'];
        
        $query = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = $id");
        $query->bindParam(1, $username, \PDO::PARAM_STR);
        $query->bindParam(2, $email, \PDO::PARAM_STR);
        if(!$query->execute()) {
            throw new Exception("Something wrong with your query");
        }
        
        return true;
        
    }
    
    public function deleteUser($id) {
        $db = $this->Db;
        
        $db->query("DELETE FROM users WHERE id = $id");
        
        return "User deleted";
        
    }
    
}
