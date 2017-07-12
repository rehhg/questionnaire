<?php

require 'vendor/autoload.php';

use App;

$userController = new App\Controllers\UserController();

$user = new App\Models\User();

$view = new App\Views\View();

echo "Hello";
