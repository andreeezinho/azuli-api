<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Cliente\ClienteTransformer;
use App\Domain\Repositories\Cliente\ClienteRepositoryInterface;

class ClienteController extends Controller {

    protected $clienteRepository;

    public function __construct(ClienteRepositoryInterface $clienteRepository){
        $this->clienteRepository = $clienteRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $clientes = $this->clienteRepository->all($params);

        return $this->respJson([
            'message' => 'Endereços listados',
            'data' => ClienteTransformer::transformArray($clientes)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'nome' => 'required|string|max:100',
            'email' => 'required|string|max:255',
            'documento' => 'required|string|max:18',
            'telefone' => 'required|string|max:15',
            'ie_rg' => 'required|string|max:10',
            'contribuinte' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $cliente = $this->clienteRepository->create($data);

        if(is_null($cliente)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar endereço'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => ClienteTransformer::transform($cliente)
        ], 201);
    }

    public function update(Request $request, $uuid){
        $data = $request->all();

        $cliente = $this->clienteRepository->findBy('uuid', $uuid);

        if(is_null($cliente)){
            return $this->respJson([
                'message' => 'Endereço não encontrado'
            ], 422);
        }

        $validate = $this->validate($data, [
            'nome' => 'required|string|max:100',
            'email' => 'required|string|max:255',
            'documento' => 'required|string|max:18',
            'telefone' => 'required|string|max:15',
            'ie_rg' => 'required|string|max:10',
            'contribuinte' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $cliente = $this->clienteRepository->update($data, $cliente->id);

        if(is_null($cliente)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar endereço'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar endereço',
            'data' => ClienteTransformer::transform($cliente)
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $cliente = $this->clienteRepository->findBy('uuid', $uuid);

        if(is_null($cliente)){
            return $this->respJson([
                'message' => 'Endereço não encontrado'
            ], 422);
        }

        $cliente = $this->clienteRepository->delete($cliente->id);

        if(!$cliente){
            return $this->respJson([
                'message' => 'Não foi possível deletar endereço'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Endereço deletado'
        ], 201);
    }

}