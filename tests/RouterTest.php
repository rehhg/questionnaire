<?php

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    
    /**
     * @dataProvider annotationReaderProvider
     */
    public function testAnnotationReader($expectException, $controllerName, $actionName) {
        if(!$expectException){
            $read = \App\Core\Router::annotationReader($controllerName, $actionName);
            $this->assertTrue($read);
        } else {
            $this->expectException(\App\Config\Exception405::class);
            $read = \App\Core\Router::annotationReader($controllerName, $actionName);
            $this->expectExceptionMessage("Forbidden 405");
        }
    }
    
    public function annotationReaderProvider() {
        return [
            [false, "\App\Controllers\UserController", "getAction"],
            [false, "\App\Controllers\BaseController", "indexAction"],
            "Forbidden 405" => [true, "\App\Controllers\UserController", "createAction"]
        ];
    }
    
}
