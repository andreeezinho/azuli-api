<?php

namespace App\Http\Controllers\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Pagamento\PagamentoTransformer;
use App\Domain\Repositories\Pagamento\PagamentoRepositoryInterface;

class PagamentoController extends Controller {

    protected $pagamentoRepository;

    public function __construct(PagamentoRepositoryInterface $pagamentoRepository){
        parent::__construct();
        $this->pagamentoRepository = $pagamentoRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $grupos = $this->pagamentoRepository->all($params);

        return $this->respJson([
            'message' => 'Formas de pagamento listadas',
            'data' => PagamentoTransformer::transformArray($grupos)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'forma' => 'required|string|max:100',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $grupo = $this->pagamentoRepository->create($data);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar forma de pagamento'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => PagamentoTransformer::transform($grupo)
        ], 201);
    }

    public function update(Request $request, $uuid){
        $data = $request->all();

        $grupo = $this->pagamentoRepository->findBy('uuid', $uuid);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Grupo não encontrado'
            ], 422);
        }

        $validate = $this->validate($data, [
            'forma' => 'required|string|max:100',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $grupo = $this->pagamentoRepository->update($data, $grupo->id);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar forma de pagamento'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar forma de pagamento',
            'data' => PagamentoTransformer::transform($grupo)
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $data = $request->all();

        $grupo = $this->pagamentoRepository->findBy('uuid', $uuid);

        if(is_null($grupo)){
            return $this->respJson([
                'message' => 'Forma de pagamento não encontrado'
            ], 422);
        }

        $grupo = $this->pagamentoRepository->delete($grupo->id);

        if(!$grupo){
            return $this->respJson([
                'message' => 'Não foi possível deletar forma de pagamento'
            ], 500);
        }

        return $this->respJson([
            'message' => 'forma de pagamento deletada'
        ], 201);
    }

}