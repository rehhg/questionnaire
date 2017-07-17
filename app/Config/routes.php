<?php
    use App\Core\App;

    return array(
        basename(App::getRootPath()) . 
        '/user/filter/by/email/(([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+)/role/([a-zA-Z]+)' 
                => 'user/filter/$1/$6',
        
        basename(App::getRootPath()) . '/user/get/([0-9]+)' => 'user/get/$1',
        basename(App::getRootPath()) . '/user/create' => 'user/create',
        'questionnaire'  => 'base/index',
    );