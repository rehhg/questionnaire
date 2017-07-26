<?php

use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase {

    protected static $db;
    protected static $userService = null;
    protected static $userModel = null;

    public static function setUpBeforeClass() {
        $data = ["name" => "hello", "email" => "d@mail.com"];
        self::$userModel = new App\Models\User($data);
        
        self::$userService = new App\Services\UserService('ut');
        self::$db = new PDO("mysql:dbname=test_qst;host=127.0.0.1", "root", "123456");
    }
    
    public static function tearDownAfterClass() {
        self::$db = null;
    }

    protected function tearDown() {
        $query = self::$db->prepare("SELECT COUNT(*) FROM users");
        $query->execute();
        
        $rows = $query->fetchColumn();
        
        if($rows > 1) {
            self::$db->query("DELETE FROM users WHERE id != 1");
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
    
    public function testCreateUser1() {
        $userService = self::$userService->createUser(self::$userModel);
        
        $this->assertInstanceOf(App\Models\User::class, self::$userModel);
    }
    
    public function testCreateUser2() {
        $userService = self::$userService->createUser(self::$userModel);
        
        $this->assertNotEmpty(self::$userModel);
    }
    
    public function testCreateUser3() {
        $userService = self::$userService->createUser(self::$userModel);
        
        $this->assertEquals(self::$userModel->name, $userService->name);
        $this->assertEquals(self::$userModel->email, $userService->email);
    }
    
    public function testUpdateUser1() {
        $userService = self::$userService->updateUser(self::$userModel);
        
        $this->assertInstanceOf(App\Models\User::class, self::$userModel);
    }
    
    public function testUpdateUser2() {
        $userService = self::$userService->updateUser(self::$userModel);
        
        $this->assertNotEmpty(self::$userModel);
    }
    
    public function testUpdateUser3() {
        $userService = self::$userService->updateUser(self::$userModel);
        
        $this->assertEquals(self::$userModel->name, $userService->name);
        $this->assertEquals(self::$userModel->email, $userService->email);
        $this->assertEquals(self::$userModel->id, $userService->id);
    }

}
