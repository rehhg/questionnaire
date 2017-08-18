<?php

    return array(
        'user/filter/by/email/(([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+)/role/([a-zA-Z]+)' 
                => 'user/filter/$1/$6',
        
        'user/update/([0-9]+)' => 'user/update/$1',
        'user/delete/([0-9]+)' => 'user/delete/$1',
        'user/get/([0-9]+)' => 'user/get/$1',
        'user/userslist' => 'user/list',
        'user/create' => 'user/create',
        
        'task/update/([0-9]+)' => 'task/update/$1',
        'task/delete/([0-9]+)' => 'task/delete/$1',
        'task/create' => 'task/create',
        'taskslist' => 'task/list',
        
        'login' => 'user/auth',
        'logout' => 'user/logout',
        
        ''  => 'home/index',
    );