<?php

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    
    /**
     * @dataProvider annotationReaderProvider
     */
    public function testAnnotationReader($expectException, $controllerName, $actionName) {
        if ($expectException) {
            $this->expectException(App\Config\Exception405::class);
        }
        
        try {
            $read = \App\Core\Router::annotationReader($controllerName, $actionName);
            $this->assertTrue($read);
        } catch (Exception $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        }
    }
    
    public function annotationReaderProvider() {
        return [
            [false, "\App\Controllers\UserController", "getAction"],
            [false, "\App\Controllers\BaseController", "indexAction"],
            ['Get 405', "\App\Controllers\UserController", "createAction"]
        ];
    }
    
}
