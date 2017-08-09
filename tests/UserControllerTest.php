<?php

use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase {
    
    public function testUserController() {
        $mock = $this->getMockBuilder("App\Controllers\UserController")
                ->setMethods(["listAction", "createAction", "updateAction", "deleteAction"])
                ->getMock();
        
        $id = 1;
        $mockUserClass = $this->getMockBuilder("App\Models\User")
                ->getMock();
        
        $mock->expects($this->once())
                ->method('listAction')
                ->will($this->returnValue($mockUserClass));
        $this->assertEquals($mockUserClass, $mock->listAction());
        
        $mock->expects($this->once())
                ->method('createAction')
                ->will($this->returnValue($mockUserClass));
        $this->assertEquals($mockUserClass, $mock->createAction());
        
        $mock->expects($this->once())
                ->method('updateAction')
                ->with($this->equalTo($id))
                ->will($this->returnValue($mockUserClass));
        $this->assertEquals($mockUserClass, $mock->updateAction($id));
        
        $mock->expects($this->once())
                ->method('deleteAction')
                ->with($this->equalTo($id))
                ->will($this->returnValue($mockUserClass));
        $this->assertEquals($mockUserClass, $mock->deleteAction($id));
    }
    
}