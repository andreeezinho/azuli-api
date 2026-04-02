<?php

namespace App\Http\Controllers;

use App\Http\Request\Response;
use App\Http\Controllers\Traits\ValidatorTrait;

class Controller {

    use ValidatorTrait;

    public function __construct(){}

    public function respJson(array $data = [], int $status = 200){
        Response::respJson($data, $status);
    }

}