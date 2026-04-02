<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Infra\Services\JWT\JWT;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Http\Transformer\User\UserTransformer;
use App\Infra\Services\Log\LogService;
use App\Infra\Services\Email\EmailService;
use App\Infra\Services\Google\GoogleAuthService;

class AuthController extends Controller {

    protected $userRepository;
    protected $googleAuthService;
    protected $fileService;
    protected $emailService;

    public function __construct(UserRepositoryInterface $userRepository, GoogleAuthService $googleAuthService, EmailService $emailService){
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->googleAuthService = $googleAuthService;
        $this->emailService = $emailService;
    }

    public function login(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'email' => 'required|email',
            'senha' => 'required|string|min:8'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $user = $this->userRepository->login(
            $validate['email'], 
            $validate['senha']
        );

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Email ou senha inválido'  
            ]);
        }

        $user = UserTransformer::transform($user);

        $token = JWT::generateToken((array)$user, 30600);
        
        LogService::logInfo("Usuário logado", ['uuid' => $user['uuid']]);
        
        return $this->respJson([
            'message' => 'Sucesso ao logar',
            'data' => $token
        ]);
    }

    public function generateGoogleAuthLink(Request $request){
        $this->googleAuthService->init();

        return $this->respJson([
            'message' => 'Sucesso ao gerar o link',
            'data' => $this->googleAuthService->generateAuthLink()
        ]);
    }

    public function loginWithGoogle(Request $request){
        $this->googleAuthService->init();
           
        $googleAuth = $this->googleAuthService->authorized($request->all()['code']);
        
        if(!$googleAuth){
            return $this->respJson([
                'message' => 'Não foi possível autenticar com Google'
            ], 401);
        }   

        $user = $this->userRepository->findBy('email', $this->googleAuthService->getClientData()->email);

        if(!is_null($user)){
            $user = UserTransformer::transform($user);

            $token = JWT::generateToken((array)$user, 30600);
            
            LogService::logInfo("Usuário LOGADO com o Google", ['uuid' => $user['uuid']]);

            return $this->respJson([
                'message' => 'Sucesso ao logar com Google',
                'data' => $token
            ]);
        }

        $user = $this->userRepository->create([
            'usuario' => $this->googleAuthService->getClientData()->givenName . $this->googleAuthService->getClientData()->familyName . uniqid(),
            'nome' => $this->googleAuthService->getClientData()->name,
            'email' => $this->googleAuthService->getClientData()->email,
            'icone' => $this->googleAuthService->getClientData()->picture,
            'ativo' => 1
        ]);
    
        if(is_null($user)){
            return $this->respJson([
                'message' => 'Erro ao cadastrar usuário'
            ], 500);
        }

        $user = UserTransformer::transform($user);

        $token = JWT::generateToken((array)$user, 30600);
        
        LogService::logInfo("Usuário CADASTRADO com Google", ['uuid' => $user['uuid']]);

        return $this->respJson([
            'message' => 'Sucesso ao logar com Google',
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

        $user = UserTransformer::transform($user);

        return $this->respJson([
            'data' => $user
        ]);
    }

}