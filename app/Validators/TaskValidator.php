<?php

namespace App\Validators;

use App\Core\Validator;
use App\Models\Model;

class TaskValidator implements Validator {
    
    public function validate(Model $task) {
        $errors = [];
        
        $date = date_parse($task->created_at);
        if(!checkdate($date['month'], $date['day'], $date['year'])){
            $errors[] = 'Please enter valid date';
        }
        
        $task->subject = filter_var($task->subject, FILTER_SANITIZE_STRING);
        $task->subject == '' ? $errors[] = 'Subject cant be empty' : '';
        $task->description = filter_var($task->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $task->description == '' ? $errors[] = 'Description cant be empty' : '';
        
        !filter_var($task->department, FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => "/^[a-z ,.'-]+$/i"])) ? 
                $errors[] = 'Please enter valid department' : true;
        !filter_var($task->tasktype, FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => "/^[a-z ,.'-]+$/i"])) ? 
                $errors[] = 'Please enter valid task type' : true;
        
        return $errors;
    }
    
}
