<?php

namespace App\Config;

use App\Http\Request\Request;
use App\Http\Request\Response;
use App\Infra\Services\JWT\JWT;

use App\Infra\Persistence\User\UserRepository;

class Auth {

    protected $request;
    protected $userRepository;

    public function __construct(){
        $this->request = new Request();
        $this->userRepository = new UserRepository();
    }

    public function check(){
        $token = $this->request->getHeaders('Authorization');

        $validate = JWT::validateToken($token);

        if(is_null($validate)){
            return false;
        }

        return true;
    }

}