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
    
    protected function setUp() {
        $sql = "INSERT INTO users VALUES (5, 'User', 'UserLast', 'user@gmail.com', "
                . "'user', 111111, 'Admin', NOW(), 0)";
        $query = self::$db->prepare($sql);
        $query->execute();
    }
    
    protected function tearDown() {
        $query = self::$db->prepare("SELECT COUNT(*) FROM users");
        $query->execute();
        
        $rows = $query->fetchColumn();
        
        if($rows > 1) {
            self::$db->query("DELETE FROM users WHERE id_user != 2");
        }  
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
        
        $service->expects($this->once())
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
        
        if(!$exception){
            $service->expects($this->once())     
                ->method("createUser")
                ->will($this->returnValue($input));
            
            $this->assertEquals($service->createUser($input), $userController->createAction());
        }
        
        $this->expectException("TypeError");
        $service->expects($this->any())     
            ->method("createUser")
            ->will($this->returnValue(new User()));

        $userController->createAction();
        $service->createUser($input);
        
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
        
        if(!$exception){
            $service->expects($this->once())     
                    ->method("updateUser")
                    ->will($this->returnValue($input));
            
            $this->assertEquals($service->updateUser($input), $userController->updateAction(5));
        }
        $this->expectException("TypeError");
        $service->expects($this->any())     
                ->method("updateUser")
                ->will($this->returnValue(new User()));

        $userController->updateAction(5);
        $service->updateUser($input);
        
        
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
     * @dataProvider providerDeleteAction
     */
    public function testForDeleteAction($exception, $input) {
        $service = $this->getMockBuilder("App\Services\UserService")
                ->setConstructorArgs(['ut'])
                ->getMock();
        $userController = new App\Controllers\UserController();
        $userController->setService($service);
        
        if(!$exception){
            $service->expects($this->once())     
                    ->method("deleteUser")
                    ->will($this->returnValue(true));

            $this->assertEquals($service->deleteUser($input), $userController->deleteAction($input));
        } else {
            $this->expectException("PDOException");
            $service->expects($this->any())     
                    ->method("deleteUser")
                    ->will($this->returnValue(false));

            $userController->deleteAction($input);
            $service->deleteUser($input);
        }
    }
    
    public function providerDeleteAction() {
        return [
            // exception    input      
            [false,         5],
            [true,          1111],
            [true,          array()],
            [true,          'sdfdg']
        ];
    }
    
}