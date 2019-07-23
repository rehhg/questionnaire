<?php

    return array(
        'user/update/([0-9]+)' => [
            'action' => 'user/update/$1',
            'roles' => ['Admin']
        ],
        'user/delete/([0-9]+)' => [
            'action' => 'user/delete/$1',
            'roles' => ['Admin']
        ],
        'user/userslist' => [
            'action' => 'user/list',
            'roles' => ['Admin']
        ],
        'user/create' => [
            'action' => 'user/create',
            'roles' => ['Admin']
        ],
        
        'task/status/([0-9]+)/([0-9]+)' => [
            'action' => 'task/status/$1/$2',
            'roles' => ['Admin', 'Manager', 'Employee']
        ],
        'task/update/([0-9]+)' => [
            'action' => 'task/update/$1',
            'roles' => ['Admin', 'Manager']
        ],
        'task/delete/([0-9]+)' => [
            'action' => 'task/delete/$1',
            'roles' => ['Admin', 'Manager']
        ],
        'task/create' => [
            'action' => 'task/create',
            'roles' => ['Admin', 'Manager']
        ],
        'taskslist' => [
            'action' => 'task/list',
            'roles' => ['Admin', 'Manager', 'Employee']
        ],
        
        'login' => [
            'action' => 'user/auth',
            'roles' => ['Admin', 'Manager', 'Employee']
        ],
        'logout' => [
            'action' => 'user/logout',
            'roles' => ['Admin', 'Manager', 'Employee']
        ],
        
        '' => [
            'action' => 'home/index',
            'roles' => ['Admin', 'Manager', 'Employee']
        ],
    );