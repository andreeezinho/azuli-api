<?php

namespace App\Http\Controllers\Tributacao;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Tributacao\TributacaoTransformer;
use App\Domain\Repositories\Tributacao\CofinsRepositoryInterface;
use App\Domain\Repositories\Tributacao\IcmsRepositoryInterface;
use App\Domain\Repositories\Tributacao\IpiRepositoryInterface;
use App\Domain\Repositories\Tributacao\PisRepositoryInterface;

class TributacaoController extends Controller {

    protected $cofinsRepository;
    protected $icmsRepository;
    protected $ipiRepository;
    protected $pisRepository;

    public function __construct(
        CofinsRepositoryInterface $cofinsRepository,
        IcmsRepositoryInterface $icmsRepository,
        IpiRepositoryInterface $ipiRepository,
        PisRepositoryInterface $pisRepository
    ){
        parent::__construct();
        $this->cofinsRepository = $cofinsRepository;
        $this->icmsRepository = $icmsRepository;
        $this->ipiRepository = $ipiRepository;
        $this->pisRepository = $pisRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $tipo = $params['tipo'].'Repository';

        unset($params['tipo']);

        $tributacoes = $this->$tipo->all($params);

        return $this->respJson([
            'message' => 'Tributações listadas',
            'data' => TributacaoTransformer::transformArray($tributacoes)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $tipo = $data['tipo'].'Repository';

        $validate = $this->validate($data, [
            'codigo' => 'required|string|max:3',
            'tributacao' => 'required|float',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $tributacao = $this->$tipo->create($data);

        if(is_null($tributacao)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar tributação'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => TributacaoTransformer::transform($tributacao)
        ], 201);
    }

    public function update(Request $request, string $uuid){
        $data = $request->all();

        $tipo = $data['tipo'].'Repository';

        $tributacao = $this->$tipo->findBy('uuid', $uuid);

        if(is_null($tributacao)){
            return $this->respJson([
                'message' => 'Tributação não encontrada'
            ], 422);
        }

        $validate = $this->validate($data, [
            'codigo' => 'required|string|max:3',
            'tributacao' => 'required|float',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $tributacao = $this->$tipo->update($data, $tributacao->id);

        if(is_null($tributacao)){
            return $this->respJson([
                'message' => 'Não foi possível editar tributação'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar tributação',
            'data' => TributacaoTransformer::transform($tributacao)
        ], 201);
    }

    public function destroy(Request $request, string $uuid){
        $data = $request->all();

        $tipo = $data['tipo'].'Repository';

        $tributacao = $this->$tipo->findBy('uuid', $uuid);

        if(is_null($tributacao)){
            return $this->respJson([
                'message' => 'Tributação não encontrada'
            ], 422);
        }

        $tributacao = $this->$tipo->delete($tributacao->id);

        if(!$tributacao){
            return $this->respJson([
                'message' => 'Não foi possível deletar tributação'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Tributação deletada'
        ], 201);
    }

}