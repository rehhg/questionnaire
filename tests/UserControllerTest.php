<?php

use PHPUnit\Framework\TestCase;

use App\Models\User;

class UserControllerTest extends TestCase {
    
    protected static $db;

    public static function setUpBeforeClass() {
        self::$db = new PDO("mysql:dbname=test_qst;host=127.0.0.1", "root", "123456");
    }
    
    public static function tearDownAfterClass() {
        self::$db = null;
    }
    
    public function insertUserForTest() {
        $sql = "INSERT INTO users VALUES (5, 'User', 'UserLast', 'user@gmail.com', "
                . "'user', 111111, 'Admin', NOW(), 0)";
        $query = self::$db->prepare($sql);
        $query->execute();
    }
    
    public function deleteUsersExceptOne() {
        $query = self::$db->prepare("SELECT COUNT(*) FROM users");
        $query->execute();
        
        $rows = $query->fetchColumn();
        
        if($rows > 2) {
            self::$db->query("DELETE FROM users WHERE id_user != 2");
        } 
    }
    
    protected function tearDown() {
        $this->insertUserForTest();
        $this->deleteUsersExceptOne();
    }
    
    public function testForListAction() {
        $service = $this->getMockBuilder("App\Services\UserService")
                ->setConstructorArgs(['ut'])
                ->getMock();
        $userController = new App\Controllers\UserController();
        $userController->setService($service);
        $data = ["id_user" => 2, "firstname" => "F_AdminDelete",
                 "lastname" => "L_AdminDelete", "email" => "admDelete@gmail.com", 
                 "username" => "admin666Del", "password" => "7c4a8d09ca3762af61e59520943dc26494f8941b", 
                 "user_role" => "Admin", "created_date" => "2017-08-12 12:48:53", 
                 "deleted" => 0];
        
        $service->expects($this->any())
                ->method("getAllUsers")
                ->will($this->returnValue([new User($data)]));
        
        $this->assertEquals($service->getAllUsers(), $userController->listAction());
    }
    
    /**
     * @dataProvider providerCreateAction
     */
    public function testForCreateAction($exception, $input) {
        $service = $this->getMockBuilder("App\Services\UserService")
                ->setConstructorArgs(['ut'])
                ->getMock();
        $userController = new App\Controllers\UserController();
        $userController->setService($service);
        
        if($exception){
            $this->expectException("TypeError");
        }
        
        $service->expects($this->any())     
            ->method("createUser")
            ->will($this->returnValue($input));
            
        $this->assertEquals($service->createUser($input), $userController->createAction());
        
    }
    
    public function providerCreateAction() {
        $_POST['firstname'] = 'User';
        $_POST['lastname'] = 'UserLast';
        $_POST['email'] = 'user@gmail.com';
        $_POST['username'] = 'user';
        $_POST['password'] = '111111';
        $_POST['confirm_password'] = '111111';
        $_POST['user_role'] = 'Admin';
        
        return [
            // exception    input      
            [false,         new User($_POST)],
            [true,          array()],
            [true,          1],
            [true,          'sdfdg']
        ];
    }
    
    /**
     * @dataProvider providerUpdateAction
     */
    public function testForUpdateAction($exception, $input) {
        $service = $this->getMockBuilder("App\Services\UserService")
                ->setConstructorArgs(['ut'])
                ->getMock();
        $userController = new App\Controllers\UserController();
        $userController->setService($service);
        
        if($exception){
            $this->expectException("TypeError");
        }
        
        $service->expects($this->any())     
                ->method("updateUser")
                ->will($this->returnValue($input));

        $this->assertEquals($service->updateUser($input), $userController->updateAction(5));
        
    }
    
    public function providerUpdateAction() {
        $_POST['id_user'] = '5';
        $_POST['firstname'] = 'User';
        $_POST['lastname'] = 'UserLast';
        $_POST['email'] = 'user@gmail.com';
        $_POST['username'] = 'user';
        $_POST['password'] = '111111';
        $_POST['confirm_password'] = '111111';
        $_POST['user_role'] = 'Admin';
        
        return [
            // exception    input     
            [false,         new User($_POST)],
            [true,          1],
            [true,          true],
            [true,          'sdfdg']
        ];
    }
    
    /**
     * @runInSeparateProcess
     * @dataProvider providerDeleteAction
     */
    public function testForDeleteAction($exception, $input) {
        $service = $this->getMockBuilder("App\Services\UserService")
                ->setConstructorArgs(['ut'])
                ->getMock();
        $userController = new App\Controllers\UserController();
        $userController->setService($service);
        
        if($exception){
            $this->expectException("TypeError");
        }

        $service->expects($this->any())     
                ->method("deleteUser")
                ->will($this->returnValue($input));

        $this->assertEquals($service->deleteUser($input), $userController->deleteAction(5));
    }
    
    public function providerDeleteAction() {
        $data = ["id_user" => 5, "firstname" => "User",
                 "lastname" => "UserLast", "email" => "user@gmail.com", 
                 "username" => "user", "password" => "111111", 
                 "user_role" => "Admin"];
        return [
            // exception    input      
            [false,         new User($data)],
            [true,          1111],
            [true,          array()],
            [true,          'sdfdg']
        ];
    }
    
}