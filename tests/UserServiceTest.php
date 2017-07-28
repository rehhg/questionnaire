<?php

use PHPUnit\Framework\TestCase;

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
    
    public function deleteMethod() {
        $query = self::$db->prepare("INSERT INTO users VALUES (2, 'ut_DELETE', 'DELETE@mail.ua', NOW())");
        $query->execute();
    }
    
    public function createMethod() {
        $query = self::$db->prepare("SELECT COUNT(*) FROM users");
        $query->execute();
        
        $rows = $query->fetchColumn();
        
        if($rows > 2) {
            self::$db->query("DELETE FROM users WHERE id NOT IN (1, 2)");
        }
    }
    
    public function updateMethod() {
        $query = self::$db->prepare("SELECT name, email FROM users WHERE id = 1");
        $query->execute();
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        if(($data['name'] != 'ut_name') || ($data['email'] != 'ut_name@gmail.com')) {
            $query = self::$db->prepare("UPDATE users SET name = 'ut_name', email = 'ut_name@gmail.com' WHERE id = 1");
            $query->execute();
        }
    }

    /**
     * @dataProvider getUserProvider
     */
    public function testGetUser($expectException, $id, $username = null, $email = null) {
        $user = self::$userService->getUser($id);

        if (!$expectException) {
            $this->assertEquals($username, $user->name);
            $this->assertEquals($email, $user->email);
        } else {
            $this->assertEmpty($user);
        }
    }

    public function getUserProvider() {
        return [
            //expectExc,    id,     username,   email
            [false,         1,      'ut_name', 'ut_name@gmail.com'],
            [true,          null],
            [true,          'invalid'],
            [true,          9999999]
        ];
    }
    
    /**
     * @dataProvider createUserProvider
    */
    public function testCreateUser($expectException, $username, $email) {
        $userData = new App\Models\User([
            "name" => $username, 
            "email" => $email
        ]);
        
        if ($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $user = self::$userService->createUser($userData);
            $query = self::$db->prepare("SELECT name, email FROM users WHERE name = ? AND email = ?");
            $query->bindParam(1, $user->name, \PDO::PARAM_STR);
            $query->bindParam(2, $user->email, \PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetch(\PDO::FETCH_ASSOC);

            $this->assertEquals($username, $result["name"]);
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
            ["'email' cannot be null",           'ut_name222', null],
            ["'name' cannot be null",            null,         'ut_222name@gmail.com'],
            ["'name' cannot be null",  null,         null]
        ];
    }
    
    /**
     * @dataProvider updateUserProvider
    */
    public function testUpdateUser($expectException, $username, $email) {
        $userData = new App\Models\User([
            "name" => $username, 
            "email" => $email,
            "id" => 1
        ]);
        
        if($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $user = self::$userService->updateUser($userData);
            $query = self::$db->prepare("SELECT name, email FROM users WHERE name = ? AND email = ?");
            $query->bindParam(1, $user->name, \PDO::PARAM_STR);
            $query->bindParam(2, $user->email, \PDO::PARAM_STR);
            $query->execute();
            
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            
            $this->assertEquals($username, $result["name"]);
            $this->assertEquals($email, $result["email"]);
        } catch (Exception $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        }
        
    }
    
    public function updateUserProvider() {
        return [
            //expectExc,                      username,     email
            [false,                           'ut_name1',   'ut_name1@gmail.com'],
            ["'email' cannot be null",           'ut_name1',   null],
            ["'name' cannot be null",            null,         'ut_name1@gmail.com'],
            ["'name' cannot be null",  null,         null]
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
