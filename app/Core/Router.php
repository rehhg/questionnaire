<?php

namespace App\Core;

class Router {

    private $routes;

    public function __construct() {
        $routesPath = ROOT . '/app/Config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Returns request string
     */
    private function getURI() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * Run the router
     */
    public function run() 
        {
            $uri = $this->getURI();
            
            //Check for this request in routes.php
            foreach($this->routes as $uriPattern => $path){
                
                
                
                //Compare $uriPattern and $uri
                if (preg_match("~^$uriPattern$~", $uri)) {
                    
                    //Get the internal path from an external path according to the rule
                    $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                    
                    //Determine which controller and action are processing the request
                    $segments = explode('/', $internalRoute);
                    
                    $controllerName = array_shift($segments).'Controller';
                    $controllerName = ucfirst($controllerName);
                    
                    $actionName = array_shift($segments) . 'Action';
                    
                    $parameters = $segments;
                    
                    //include file of the controller
                    $controllerFile = ROOT .'/app/Controllers/'.
                            $controllerName.'.php';
                    
                    if (file_exists($controllerFile)){
                        include_once($controllerFile);
                    }
                    
                    $controllerFullName = "\\App\\Controllers\\" . $controllerName;

                    //Create an object, call a method (Action)
                    $controllerObject = new $controllerFullName;
                    $result = call_user_func_array(array($controllerObject, $actionName), 
                            $parameters);
                    
                    if($result == null){
                        break;
                    }
                }
            }
            
            // throw 404 page Not Found if there are no routes
            if(!isset($internalRoute)) {
                header("HTTP/1.0 404 Not Found");
                include ROOT . '/app/Config/404.php';
                die();
            }
            
        }

}
