<?php

namespace App\Core;

use App\Models\Model;

interface Validator {
    
    public function validate(Model $model);
    
}
