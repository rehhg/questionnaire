<?php

use PHPUnit\Framework\TestCase;

use App\Models\User;

class UserControllerTest extends TestCase {
    
    /**
     * @dataProvider providerListAction
     */
    public function testForListAction($input) {
        $service = $this->getMockBuilder("App\Services\UserService")
                ->setConstructorArgs(['ut'])
                ->getMock();
        $userController = new App\Controllers\UserController();
        $userController->setService($service);
        
        $service->expects($this->once())     
                ->method("getAllUsers")
                ->will($this->returnValue(new User()));
        
        $userController->listAction($service->getAllUsers($input));
        
    }
    
    public function providerListAction() {
        return [
            // input      
            [null],
            [new User()],
            [1],
            ['deleteAction']
        ];
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
                ->will($this->returnValue(new User()));
            
            $userController->createAction($service->createUser($input));
        } else {
            $this->expectException("TypeError");
            $service->expects($this->any())     
                ->method("createUser")
                ->will($this->returnValue(new User()));
            
            $userController->createAction($service->createUser($input));
        }
    }
    
    public function providerCreateAction() {
        return [
            // exception    input      
            [false,         null],
            [false,         new User()],
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

            $user = $service->updateUser($input);
            $userController->updateAction($user->id_user);
        } else {
            $this->expectException("TypeError");
            $service->expects($this->any())     
                    ->method("updateUser")
                    ->will($this->returnValue(new User()));

            $userController->updateAction($service->updateUser($input));
        }
        
    }
    
    public function providerUpdateAction() {
        return [
            // exception    input     
            [false,         new User(["id_user" => 4])],
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
            $this->expectException("PDOException");
            $service->expects($this->once())     
                    ->method("deleteUser")
                    ->will($this->returnValue($input));

            $userController->deleteAction($service->deleteUser($input));
        } else {
            $this->expectException("PDOException");
            $service->expects($this->any())     
                    ->method("deleteUser")
                    ->will($this->returnValue(false));

            $userController->deleteAction($service->deleteUser($input));
        }
    }
    
    public function providerDeleteAction() {
        return [
            // exception    input      
            [false,         111],
            [true,          new User()],
            [true,          array()],
            [true,          'sdfdg']
        ];
    }
    
    /**
     * @dataProvider providerControllerTest
     */
    public function testUserControllerReturningValues($action, $id) {
        $mock = $this->getMockBuilder("App\Controllers\UserController")
                ->setMethods(["$action", "setService"])
                ->enableOriginalConstructor()
                ->getMock();
        $mockUserClass = $this->getMockBuilder("App\Models\User")
                ->getMock();
        
        if ($id == null) {
            $mock->expects($this->once())
                    ->method("$action")
                    ->will($this->returnValue($mockUserClass));
            $this->assertEquals($mockUserClass, $mock->{$action}());
        } else {
            $mock->expects($this->once())
                    ->method("$action")
                    ->with($this->equalTo($id))
                    ->will($this->returnValue($mockUserClass));
            $this->assertEquals($mockUserClass, $mock->{$action}($id));
        }
    }
    
    public function providerControllerTest() {
        return [
            // action           id    
            ['listAction',      null],
            ['createAction',    null],
            ['updateAction',    10],
            ['deleteAction',    10]
        ];
    }
    
}