<?php

namespace App\Controllers;

use App\Models\Model;
use App\Services\Service;

class BaseController {

    public $service;

    public function setService(Service $service) {
        $this->service = $service;
    }

    protected function validate(Model $model) {
        $modelName = get_class($model);
        $modelClassName = substr($modelName, strrpos($modelName, "\\") + 1);
        $validatorName = "App\\Validators\\" . $modelClassName . 'Validator';
        $validator = new $validatorName;

        return $validator->validate($model);
    }

}
