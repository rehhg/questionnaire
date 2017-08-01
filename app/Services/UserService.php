<?php

namespace App\Services;

use App\Models\User;
use PDOException;

class UserService extends Service {
    
    public function getAllUsers() {
        $query = $this->db->prepare("SELECT * FROM users");
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data = $query->fetchAll(\PDO::FETCH_OBJ);
        
        return $data;
    }
    
    public function createUser(User $user = null) {
        $query = $this->db->prepare("INSERT INTO users VALUES (NULL, ?, ?, ?, ?, ?, ?, NOW(), 0)");
        $query->bindParam(1, $user->firstname, \PDO::PARAM_STR);
        $query->bindParam(2, $user->lastname, \PDO::PARAM_STR);
        $query->bindParam(3, $user->email, \PDO::PARAM_STR);
        $query->bindParam(4, $user->username, \PDO::PARAM_STR);
        $query->bindParam(5, $user->password, \PDO::PARAM_STR);
        $query->bindParam(6, $user->user_role, \PDO::PARAM_STR);
        
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        } else {
            $query = $this->db->prepare("SELECT id_user, created_date FROM users WHERE username = ? AND email = ?");
            $query->bindParam(1, $user->username, \PDO::PARAM_STR);
            $query->bindParam(2, $user->email, \PDO::PARAM_STR);
            if(!$query->execute()) {
                throw new PDOException($this->getDbError($query));
            }
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            
            $user->id_user = $result["id_user"];
            $user->created_date = $result["created_date"];
        }
        
        return $user;
    }
    
    public function getUser($id) {
        $query = $this->db->prepare("SELECT * FROM users WHERE id_user = ?");
        $query->bindParam(1, $id, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }
    
    public function updateUser(User $user = null) {
        $query = $this->db->prepare("UPDATE users SET firstname = ?, lastname = ?, "
                . "email = ?, username = ?, password = ?, user_role = ? WHERE id_user = ?");
        $query->bindParam(1, $user->firstname, \PDO::PARAM_STR);
        $query->bindParam(2, $user->lastname, \PDO::PARAM_STR);
        $query->bindParam(3, $user->email, \PDO::PARAM_STR);
        $query->bindParam(4, $user->username, \PDO::PARAM_STR);
        $query->bindParam(5, $user->password, \PDO::PARAM_INT);
        $query->bindParam(6, $user->user_role, \PDO::PARAM_STR);
        $query->bindParam(7, $user->id_user, \PDO::PARAM_INT);
        if(!$query->execute()) {
            throw new PDOException($this->getDbError($query));
        }
        
        return $user;
        
    }
    
    /**
     * @Route("/questionnaire/user/delete/{id_user}", name="delete")
     */
    public function deleteUser($id_user) {
        if(gettype($id_user) === 'integer'){
            $query = $this->db->prepare("DELETE FROM users WHERE id_user = ?");
            $query->bindParam(1, $id_user, \PDO::PARAM_INT);
            $query->execute();
            if($query->rowCount() == 0) {
                throw new PDOException("There are no user with id = $id_user");
            }
        } else {
            throw new PDOException('id need to be an integer');
        }
        
        return true;
        
    }
    
}