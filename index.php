<?php

ini_set('display errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use App\Config\Exception404, App\Config\Exception405;
use App\Core\App;

try{
    $router = new \App\Core\Router();
    $router->run();
}
catch (Exception404 $e) { include App::getRootPath() . '/app/Config/404.php'; }
catch (Exception405 $e) { include App::getRootPath() . '/app/Config/405.php'; }