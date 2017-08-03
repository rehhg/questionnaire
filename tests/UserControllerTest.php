<?php

use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase {
    
    public function testGetAction() {
        $mock = $this->getMockBuilder("App\Controllers\UserController")
                ->setMethods(['getAction', "listAction", "createAction", "updateAction", "deleteAction"])
                ->disableArgumentCloning()
                ->getMock();
        $mock->expects($this->any())
                ->method('getAction')
                ->will($this->returnValue('array'));
        
    }
    
}