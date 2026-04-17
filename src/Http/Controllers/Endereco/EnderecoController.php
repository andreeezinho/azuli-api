<?php

namespace App\Http\Controllers\Endereco;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Endereco\EnderecoTransformer;
use App\Domain\Repositories\Endereco\EnderecoRepositoryInterface;

class EnderecoController extends Controller {

    protected $enderecoRepository;

    public function __construct(EnderecoRepositoryInterface $enderecoRepository){
        $this->enderecoRepository = $enderecoRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $enderecos = $this->enderecoRepository->all($params);

        return $this->respJson([
            'message' => 'Endereços listados',
            'data' => EnderecoTransformer::transformArray($enderecos)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'cep' => 'required|string|max:10',
            'uf' => 'required|string|max:2',
            'codigo' => 'required|int',
            'cidade' => 'required|string|max:150',
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $endereco = $this->enderecoRepository->create($data);

        if(is_null($endereco)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar endereço'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => EnderecoTransformer::transform($endereco)
        ], 201);
    }

    public function update(Request $request, $uuid){
        $data = $request->all();

        $endereco = $this->enderecoRepository->findBy('uuid', $uuid);

        if(is_null($endereco)){
            return $this->respJson([
                'message' => 'Endereço não encontrado'
            ], 422);
        }

        $validate = $this->validate($data, [
            'cep' => 'required|string|max:10',
            'uf' => 'required|string|max:2',
            'codigo' => 'required|int',
            'cidade' => 'required|string|max:150',
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $endereco = $this->enderecoRepository->update($data, $endereco->id);

        if(is_null($endereco)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar endereço'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar endereço',
            'data' => EnderecoTransformer::transform($endereco)
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $endereco = $this->enderecoRepository->findBy('uuid', $uuid);

        if(is_null($endereco)){
            return $this->respJson([
                'message' => 'Endereço não encontrado'
            ], 422);
        }

        $endereco = $this->enderecoRepository->delete($endereco->id);

        if(!$endereco){
            return $this->respJson([
                'message' => 'Não foi possível deletar endereço'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Endereço deletado'
        ], 201);
    }

}