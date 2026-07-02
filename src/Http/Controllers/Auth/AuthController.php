<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Infra\Services\JWT\JWT;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Http\Transformer\User\UserTransformer;
use App\Infra\Services\Log\LogService;
use App\Infra\Services\Email\EmailService;

class AuthController extends Controller {

    protected $userRepository;
    protected $fileService;
    protected $emailService;
    protected $userTransformer;

    public function __construct(UserRepositoryInterface $userRepository, EmailService $emailService, UserTransformer $userTransformer){
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->emailService = $emailService;
        $this->userTransformer = $userTransformer;
    }

    public function login(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'usuario' => 'required|string',
            'senha' => 'required|string|min:8'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $user = $this->userRepository->login(
            $validate['usuario'], 
            $validate['senha']
        );

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário ou senha inválido'  
            ]);
        }

        $user = $this->userTransformer->transform($user);

        $token = JWT::generateToken((array)$user, 30600);
        
        LogService::logInfo("Usuário logado", ['uuid' => $user['uuid']]);
        
        return $this->respJson([
            'message' => 'Sucesso ao logar',
            'data' => $token
        ]);
    }

    public function profile(Request $request){
        $userData = $request->getHeaders('Authorization');

        $userValidate = JWT::validateToken($userData);

        if(is_null($userValidate)){
            return $this->respJson([
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $user = $this->userRepository->findBy('uuid', $userValidate['uuid']);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        $user = $this->userTransformer->transform($user);

        return $this->respJson([
            'data' => $user
        ]);
    }

}