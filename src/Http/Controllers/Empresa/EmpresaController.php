<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Empresa\EmpresaTransformer;
use App\Domain\Models\Empresa\Empresa;
use App\Domain\Repositories\Empresa\EmpresaRepositoryInterface;

class EmpresaController extends Controller {

    protected $empresaRepository;

    public function __construct(EmpresaRepositoryInterface $empresaRepository){
        $this->empresaRepository = $empresaRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $empresas = $this->empresaRepository->all($params);

        return $this->respJson([
            'message' => 'Empresas listados',
            'data' => EmpresaTransformer::transformArray($empresas)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'documento' => 'required|string|max:18',
            'ie_rg' => 'required|string|max:10',
            'num_serie_nfe' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $empresa = $this->empresaRepository->create($data);

        if(is_null($empresa)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar empresa'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => empresaTransformer::transform($empresa)
        ], 201);
    }

    public function update(Request $request, $uuid){
        $data = $request->all();

        $empresa = $this->empresaRepository->findBy('uuid', $uuid);

        if(is_null($empresa)){
            return $this->respJson([
                'message' => 'Empresa não encontrada'
            ], 422);
        }

        $validate = $this->validate($data, [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'documento' => 'required|string|max:18',
            'ie_rg' => 'required|string|max:10',
            'num_serie_nfe' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $empresa = $this->empresaRepository->update($data, $empresa->id);

        if(is_null($empresa)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar empresa'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar empresa',
            'data' => empresaTransformer::transform($empresa)
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $empresa = $this->empresaRepository->findBy('uuid', $uuid);

        if(is_null($empresa)){
            return $this->respJson([
                'message' => 'Empresa não encontrada'
            ], 422);
        }

        $empresa = $this->empresaRepository->delete($empresa->id);

        if(!$empresa){
            return $this->respJson([
                'message' => 'Não foi possível deletar empresa'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Empresa deletada'
        ], 201);
    }

}