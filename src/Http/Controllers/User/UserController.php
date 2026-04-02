<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Http\Transformer\User\UserTransformer;
use App\Infra\Services\File\FileService;
use App\Infra\Services\Log\LogService;
use App\Infra\Services\Email\EmailService;

class UserController extends Controller {

    protected $userRepository;
    protected $fileService;
    protected $emailService;

    public function __construct(UserRepositoryInterface $userRepository, FileService $fileService, EmailService $emailService){
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->fileService = $fileService;
        $this->emailService = $emailService;
    }

    public function index(Request $request){
        $params = $request->getQueryParams();

        $users = $this->userRepository->all($params);
        
        return $this->respJson([
            'message' => "Usuários listados",
            'data' => UserTransformer::transformArray($users)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'usuario' => 'required|string|max:20',
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'cpf' => 'required|string|max:14',
            'telefone' => 'string|max:15',
            'senha' => 'required|string|min:8',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $user = $this->userRepository->create($data);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Erro ao cadastrar usuário'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => UserTransformer::transform($user)
        ], 201);
    }

    public function update(Request $request, string $uuid){
        $user = $this->userRepository->findBy('uuid', $uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $data = $request->all();

        $validate = $this->validate($data, [
            'usuario' => 'required|string|max:20',
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'cpf' => 'required|string|max:14',
            'telefone' => 'required|string|max:15',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $update = $this->userRepository->update($data, $user->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao editar o usuário'
            ], 500);
        }
        
        return $this->respJson([
            'message' => 'Sucesso ao atualizar o usuário',
            'data' => UserTransformer::transform($update)
        ], 201);
    }

    public function updatePassword(Request $request, $uuid){
        $user = $this->userRepository->findBy('uuid', $uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $data = $request->all();

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

        return $this->respJson([
            'message' => 'Sucesso ao atualizar senha'
        ], 201);
    }

    public function updateIcon(Request $request, $uuid){
        $user = $this->userRepository->findBy('uuid', $uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $data = $request->getFileParams();

        $validate = $this->validate($data, [
            'icone' => 'required'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $saveIcon = $this->fileService->uploadFile($data['icone'], '/img/users');
        
        if(is_null($saveIcon)){
            return $this->respJson([
                'message' => 'Não foi possível salvar o arquivo'
            ], 500);
        }

        $update = $this->userRepository->updateIcone(['icone' => $saveIcon['hash_name']], $user->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao atualizar o icone'
            ], 500);
        }
        
        return $this->respJson([
            'message' => 'Sucesso ao atualizar icone',
            'data' => $saveIcon['hash_name']
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $user = $this->userRepository->findBy('uuid', $uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $delete = $this->userRepository->delete($user->id);

        if(!$delete){
            return $this->respJson([
                'message' => 'Erro ao excluir o usuário'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao excluir usuário'
        ], 201);
    }

}