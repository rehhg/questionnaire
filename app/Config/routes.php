<?php

    return array(
        'user/filter/by/email/(([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+)/role/([a-zA-Z]+)' 
                => 'user/filter/$1/$6',
        
        'user/get/([0-9]+)' => 'user/get/$1',
        'user/create' => 'user/create',
        'user/update/([0-9]+)' => 'user/update/$1',
        'user/delete/([0-9]+)' => 'user/delete/$1',
        'user/userslist' => 'user/list',
        ''  => 'home/index',
    );