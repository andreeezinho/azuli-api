<?php

namespace App\Http\Controllers\GrupoProduto;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\GrupoProduto\GrupoProdutoTransformer;
use App\Domain\Repositories\GrupoProduto\GrupoProdutoRepositoryInterface;

class GrupoProdutoController extends Controller {

    protected $grupoProdutoRepository;

    public function __construct(GrupoProdutoRepositoryInterface $grupoProdutoRepository){
        parent::__construct();
        $this->grupoProdutoRepository = $grupoProdutoRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $grupos = $this->grupoProdutoRepository->all($params);

        return $this->respJson([
            'message' => 'Grupos listados',
            'data' => GrupoProdutoTransformer::transformArray($grupos)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'nome' => 'required|string|max:100',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $grupo = $this->grupoProdutoRepository->create($data);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar grupo de produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => GrupoProdutoTransformer::transform($grupo)
        ], 201);
    }

    public function update(Request $request, $uuid){
        $data = $request->all();

        $grupo = $this->grupoProdutoRepository->findBy('uuid', $uuid);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Grupo não encontrado'
            ], 422);
        }

        $validate = $this->validate($data, [
            'nome' => 'required|string|max:100',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $grupo = $this->grupoProdutoRepository->update($data, $grupo->id);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar grupo de produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar grupo de produto',
            'data' => GrupoProdutoTransformer::transform($grupo)
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $grupo = $this->grupoProdutoRepository->findBy('uuid', $uuid);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Grupo de produto não encontrado'
            ], 422);
        }

        $grupo = $this->grupoProdutoRepository->delete($grupo->id);

        if(!$grupo){
            return $this->respJson([
                'message' => 'Não foi possível deletar grupo de produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Grupo de produto deletado'
        ], 201);
    }

}