<?php

ini_set('display errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

$router = new \App\Core\Router();
$router->run();