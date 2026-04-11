<?php

namespace App\Http\Controllers\RecuperarSenha;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Services\Log\LogService;
use App\Infra\Services\Email\EmailService;

class RecuperarSenhaController extends Controller {

    protected $recuperarSenhaRepository;
    protected $userRepository;
    protected $emailService;

    public function __construct(RecuperarSenhaRepositoryInterface $recuperarSenhaRepository, UserRepositoryInterface $userRepository, EmailService $emailService){
        parent::__construct();
        $this->recuperarSenhaRepository = $recuperarSenhaRepository;
        $this->userRepository = $userRepository;
        $this->emailService = $emailService;
    }

    public function sendVerificationCode(Request $request){
        $data = $request->all();

        $user = $this->userRepository->findBy('email', $data['email']);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }   

        $findCode = $this->recuperarSenhaRepository->findBy('usuarios_id', $user->id);

        if($findCode){
            $this->recuperarSenhaRepository->delete($findCode->id);
        }

        $create = $this->recuperarSenhaRepository->create([
            'usuarios_id' => $user->id,
            'codigo' => rand(100000, 999999),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
        ]);

        if(is_null($create)){
            return $this->respJson([
                'message' => 'Erro ao criar o código'
            ], 500);
        }

        $sendEmail = $this->emailService->sendPasswordReset($user->email, $user->usuario, $create->codigo, $create->expires_at);

        if(!$sendEmail){
            return $this->respJson([
                'message' => 'Erro ao enviar o email'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Código enviado para o email'
        ]);
    }

    public function changePassword(Request $request){
        $data = $request->all();

        $user = $this->userRepository->findBy('email', $data['email']);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        if(!$this->recuperarSenhaRepository->verifyCode($data['codigo'], $user->id)){
            return $this->respJson([
                'message' => 'Código inválido ou expirado'
            ], 422);
        }

        $validate = $this->validate($data, [
            'senha' => 'required|string|min:8'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $update = $this->userRepository->updateSenha($data, $user->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao atualizar senha'
            ], 422);
        }

        LogService::logInfo("Usuário recuperou senha através do email", ['uuid' => $user->uuid]);

        return $this->respJson([
            'message' => 'Sucesso ao atualizar senha'
        ], 201);
    }

}