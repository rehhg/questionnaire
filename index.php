<?php

ini_set('display errors', 1);
error_reporting(E_ALL);

define('ROOT', dirname(__FILE__));
require 'vendor/autoload.php';

$router = new \App\Core\Router();
$router->run();