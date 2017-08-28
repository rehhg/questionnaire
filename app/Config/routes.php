<?php

    return array(
        'user/update/([0-9]+)' => 'user/update/$1',
        'user/delete/([0-9]+)' => 'user/delete/$1',
        'user/userslist' => 'user/list',
        'user/create' => 'user/create',
        
        'task/status/([0-9]+)/([0-9]+)' => 'task/status/$1/$2',
        'task/update/([0-9]+)' => 'task/update/$1',
        'task/delete/([0-9]+)' => 'task/delete/$1',
        'task/create' => 'task/create',
        'taskslist' => 'task/list',
        
        'login' => 'user/auth',
        'logout' => 'user/logout',
        
        ''  => 'home/index',
    );