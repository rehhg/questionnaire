<?php

namespace App\Validators;

use App\Core\Validator;
use App\Models\Model;

class UserValidator implements Validator {

    public function validate(Model $user) {
        $errors = [];
        
        !filter_var($user->username, FILTER_VALIDATE_REGEXP, 
                array("options" => ["regexp" => "/^[a-zA-Z](([\._\-][a-zA-Z0-9])|[a-zA-Z0-9])*[a-z0-9]$/"])) ? 
                $errors[] = 'Please enter valid username' : true;  
        !filter_var($user->email, FILTER_VALIDATE_EMAIL) ? $errors[] = 'Please enter valid email' : true;
        !filter_var($user->firstname, FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => "/^[a-z ,.'-]+$/i"])) ?
                $errors[] = 'Please enter valid firstname' : true;
        !filter_var($user->lastname, FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => "/^[a-z ,.'-]+$/i"])) ? 
                $errors[] = 'Please enter valid lastname' : true;
        !filter_var($user->user_role, FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => "/^[a-z ,.'-]+$/i"])) ? 
                $errors[] = 'Please enter valid user_role' : true;
        
        if($user->password === $user->confirm_password) {
            !filter_var($user->password, FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/.{6,25}/"))) ? 
                $errors[] = 'Please enter valid password' : true;
            !filter_var($user->confirm_password, FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/.{6,25}/"))) ? 
                $errors[] = 'Please enter valid password' : true;  
        } else {
            $errors[] = 'Passwords did not match';
        }
        
        return $errors;
    }

}
