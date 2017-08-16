<?php

namespace App\Controllers;

use App\Models\Model;
use App\Services\Service;

class BaseController {

    protected $service;
    const VALIDATOR_PATH = 'App\\Validators\\';

    public function setService(Service $service) {
        $this->service = $service;
    }
    
    protected function redirect($location) {
        header("Location: $location");
    }

    protected function validate(Model $model) {
        $modelName = get_class($model);
        $modelClassName = substr($modelName, strrpos($modelName, "\\") + 1);
        $validatorName = self::VALIDATOR_PATH . $modelClassName . 'Validator';
        $validator = new $validatorName;

        return $validator->validate($model);
    }

}
