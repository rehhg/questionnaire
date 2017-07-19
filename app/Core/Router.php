<?php

namespace App\Core;

use App\Config\Exception404;
use App\Config\Exception405;

class Router {

    const CONTROLLERS_DIR = '/app/Controllers/';
    const CONTROLLERS_DIR_NAMESPACE = "\\App\\Controllers\\";
    const BASE_URL = '/questionnaire/';

    private $data;
    private $routes;
    private $internalRoute;
    private $controllerFullName;
    private $actionName;
    private $parameters;

    public function __construct() {
        $routesPath = App::getRootPath() . '/app/Config/routes.php';
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
     * Reads the annotation
     */
    public static function annotationReader($controllerName, $actionName) {
        $reader = new \DocBlockReader\Reader($controllerName, $actionName);
        $params = $reader->getParameters();
        
        if (is_array($params["method"])) {
            if (!in_array($_SERVER['REQUEST_METHOD'], $params["method"])) {
                throw new Exception405("Get 405.");
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] != $params["method"]) {
                throw new Exception405("Get 405.");
            }
        }
        
        return true;
    }

    /**
     * Search URI request and return array with data if exist
     */
    private function searchUriRequest($uriPattern, $uri, $path) {
        if (preg_match("~^$uriPattern$~", $uri)) {

            $this->controllerFullName = $this->parseRoute($uriPattern, $path, $uri);

            //Create an object, call a method (Action)
            $controllerObject = new $this->controllerFullName;
            
            if(Router::annotationReader($this->controllerFullName, $this->actionName)) {
                $this->data = call_user_func_array(array($controllerObject, $this->actionName), $this->parameters);
            }
            
            return $this->data;
        }
    }

    /**
     * Get the internal path from an external path according to the rule
     */
    private function parseRoute($uriPattern, $path, $uri) {
        $this->internalRoute = preg_replace("~$uriPattern~", $path, $uri);

        //Determine which controller and action are processing the request
        $segments = explode('/', $this->internalRoute);

        $controllerName = array_shift($segments) . 'Controller';
        $controllerName = ucfirst($controllerName);

        $this->actionName = array_shift($segments) . 'Action';

        $this->parameters = $segments;

        //include file of the controller
        $controllerFile = App::getRootPath() . self::CONTROLLERS_DIR . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            include_once($controllerFile);
        }

        $controllerFullName = self::CONTROLLERS_DIR_NAMESPACE . $controllerName;

        return $controllerFullName;
    }

    private function getTemplate($data) {
        $this->data = $data;
        
        $reader = new \DocBlockReader\Reader($this->controllerFullName, $this->actionName);
        $template = $reader->getParameter("template");

        $loader = new \Twig_Loader_Filesystem('app/Views');
        $twig = new \Twig_Environment($loader);
        
        $function = new \Twig_Function('base_url', function ($url) {
            
            return self::BASE_URL . $url;
        });
        
        $twig->addFunction($function);
        
        echo $twig->render($template, array("data" => $data));
    }

        /**
     * Run the router
     */
    public function run() {
        $result = null;
        $uri = $this->getURI();

        //Check for this request in routes.php
        foreach ($this->routes as $uriPattern => $path) {

            if($result = $this->searchUriRequest($uriPattern, $uri, $path)) {
                 break;
            }
        }
        
        if (!isset($this->internalRoute)) {
            throw new Exception404("Get 404.");
        } else {
            $this->getTemplate($this->data);
        }
    }

}
