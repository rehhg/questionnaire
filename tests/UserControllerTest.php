<?php

use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase {
    
    /**
     * @dataProvider providerControllerTest
     */
    public function testUserController($action, $id) {
        $mock = $this->getMockBuilder("App\Controllers\UserController")
                ->setMethods(["$action"])
                ->enableOriginalConstructor()
                ->setConstructorArgs(['ut'])
                ->getMock();
        $mockUserClass = $this->getMockBuilder("App\Models\User")
                ->getMock();
        //$service = $this->getMockBuilder($className);
        
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