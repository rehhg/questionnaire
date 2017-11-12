<?php

session_start();

require 'vendor/autoload.php';

use App\Config\Exception404, App\Config\Exception405;
use App\Core\App;
use App\Core\Router;

try{
    $router = new Router();
    $router->run();
}
catch (Exception404 $e) { include App::getRootPath() . '/app/Config/404.php'; }
catch (Exception405 $e) { include App::getRootPath() . '/app/Config/405.php'; }
catch (PDOException $e) { echo $e->getMessage() . ' in file ' .  $e->getFile() . ' in line ' . $e->getLine(); }