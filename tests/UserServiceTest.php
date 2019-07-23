<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserServiceTest extends TestCase {

    protected static $db;
    protected static $userService = null;

    public static function setUpBeforeClass() {        
        self::$userService = new App\Services\UserService('ut');
        self::$db = new PDO("mysql:dbname=test_qst;host=127.0.0.1", "root", "123456");
    }
    
    public static function tearDownAfterClass() {
        self::$db = null;
    }

    protected function tearDown() {
        $this->createMethod();
        $this->updateMethod();
        $this->deleteMethod();   
    }
    
    private function deleteMethod() {
        $sql = "INSERT INTO users VALUES (2, 'F_AdminDelete', 'L_AdminDelete', 'admDelete@gmail.com', "
                . "'admin666Del', SHA(123456), 'Admin', NOW(), 0)";
        $query = self::$db->prepare($sql);
        $query->execute();
    }
    
    private function createMethod() {
        $query = self::$db->prepare("SELECT COUNT(*) FROM users");
        $query->execute();
        
        $rows = $query->fetchColumn();
        
        if($rows > 2) {
            self::$db->query("DELETE FROM users WHERE id_user NOT IN (1, 2)");
        }
    }
    
    private function updateMethod() {
        $query = self::$db->prepare("SELECT username, email FROM users WHERE id_user = 1");
        $query->execute();
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        if(($data['username'] != 'admin666') || ($data['email'] != 'admin@gmail.com')) {
            $query = self::$db->prepare("UPDATE users SET username = 'admin666', "
                    . "email = 'admin@gmail.com' WHERE id_user = 1");
            $query->execute();
        }
    }
    
    /**
     * @dataProvider checkUserProvider
     */
    public function testCheckIfUserExist($expectException, $param, $userData) {
        if($expectException) {
            $this->expectException("TypeError");
        }
        $user = self::$userService->checkIfUserExist($param);
        
        $this->assertEquals($userData, $user);
    }

    public function checkUserProvider() {
        $userData = new User([
            "id_user" => "2",
            "firstname" => "F_AdminDelete",
            "lastname" => "L_AdminDelete",
            "email" => "admDelete@gmail.com",
            "username" => "admin666Del", 
            "password" => "7c4a8d09ca3762af61e59520943dc26494f8941b",
            "user_role" => "Admin",
            "created_date" => "2017-08-12 12:48:53",
            "deleted" => "0"
        ]);
        $data = ["username" => "Admin666Del", "password" => "123456"];
        return [
            //expectExc,    param            user
            [false,         new User($data), $userData],
            [true,          null,            $userData],
            [true,          'invalid',       $userData],
            [true,          9999999,         $userData]
        ];
    }

    /**
     * @dataProvider getUserProvider
     */
    public function testGetUser($expectException, $id, $username = null, $email = null) {
        $user = self::$userService->getUser($id);

        if (!$expectException) {
            $this->assertEquals($username, $user->username);
            $this->assertEquals($email, $user->email);
        } else {
            $this->assertEmpty($user);
        }
    }

    public function getUserProvider() {
        return [
            //expectExc,    id,     username,   email
            [false,         1,      'admin666', 'admin@gmail.com'],
            [true,          null],
            [true,          'invalid'],
            [true,          9999999]
        ];
    }
    
    /**
     * @dataProvider createUserProvider
    */
    public function testCreateUser($expectException, $username, $email) {
        $userData = new User([
            "firstname" => "Test",
            "lastname" => "TestLastname",
            "email" => $email,
            "username" => $username, 
            "password" => 123456,
            "user_role" => "Employee"
        ]);
        
        if ($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $user = self::$userService->createUser($userData);
            $query = self::$db->prepare("SELECT username, email FROM users WHERE username = ? AND email = ?");
            $query->bindParam(1, $user->username, \PDO::PARAM_STR);
            $query->bindParam(2, $user->email, \PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetch(\PDO::FETCH_ASSOC);
            $this->assertEquals($username, $result["username"]);
            $this->assertEquals($email, $result["email"]);
        } catch (PDOException $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        } 

    }
    
    public function createUserProvider() {
        return [
            //expectExc,                      username,     email
            [false,                           'ut_name222', 'ut_222name@gmail.com'],
            ["'email' cannot be null",        'ut_name222', null],
            ["'username' cannot be null",      null,         'ut_222name@gmail.com'],
            ["'email' cannot be null",      null,         null]
        ];
    }
    
    /**
     * @dataProvider updateUserProvider
    */
    public function testUpdateUser($expectException, $username, $email) {
        $userData = new User([
            "firstname" => "AdminName",
            "lastname" => "AdminSurname",
            "email" => $email,
            "username" => $username, 
            "password" => sha1(123456),
            "user_role" => "Admin",
            "id_user" => 1
        ]);
        
        if($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $user = self::$userService->updateUser($userData);
            $query = self::$db->prepare("SELECT username, email FROM users WHERE username = ? AND email = ?");
            $query->bindParam(1, $user->username, \PDO::PARAM_STR);
            $query->bindParam(2, $user->email, \PDO::PARAM_STR);
            $query->execute();
            
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            
            $this->assertEquals($username, $result["username"]);
            $this->assertEquals($email, $result["email"]);
        } catch (Exception $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        }
        
    }
    
    public function updateUserProvider() {
        return [
            //expectExc,                      username,             email
            [false,                           'Updated_admin666',   'Updated_admin@gmail.com'],
            ["'email' cannot be null",         'ut_name1',           null],
            ["'username' cannot be null",      null,                'ut_name1@gmail.com'],
            ["'email' cannot be null",         null,                 null]
        ];
    }

    /**
     * @dataProvider deleteUserProvider
    */
    public function testDeleteUser($expectException, $id) {
        if($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $user = self::$userService->deleteUser($id);
            $this->assertTrue($user);
        } catch (Exception $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        }
    }
    
    public function deleteUserProvider() {
        return [
            //expectExc,                          id
            [false,                               2],
            ['There are no user with id',        0256],
            ['id need to be an integer',          'hello'],
            ['id need to be an integer',            null]
        ];
    }
    
}
