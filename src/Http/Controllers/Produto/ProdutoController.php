<?php

namespace App\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Http\Transformer\Produto\ProdutoTransformer;

class ProdutoController extends Controller {

    protected $produtoRepository;

    public function __construct(ProdutoRepositoryInterface $produtoRepository){
        parent::__construct();
        $this->produtoRepository = $produtoRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $produtos = $this->produtoRepository->all($params);

        return $this->respJson([
            'message' => 'Produtos listados',
            'data' => ProdutoTransformer::transformArray($produtos)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'nome' => 'required|string|max:100',
            'codigo' => 'required|max:13',
            'preco' => 'required|float',
            'estoque' => 'required|float',
            'tipo' => 'required|string',
            'quant_entrada' => 'required|float',
            'quant_saida' => 'required|float',
            'grupo_produto_id' => 'required|int',
            'icms_id' => 'required|int',
            'ipi_id' => 'required|int',
            'pis_id' => 'required|int',
            'cofins_id' => 'required|int',
            'cfop' => 'required|int',
            'ncm' => 'int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $produto = $this->produtoRepository->create($data);

        if(is_null($produto)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => ProdutoTransformer::transform($produto)
        ], 201);
    }

    public function update(Request $request, string $uuid){
        $produto = $this->produtoRepository->findBy('uuid', $uuid);

        if(is_null($produto)){
            return $this->respJson([
                'message' => 'Produto não encontrado'
            ], 422);
        }

        $data = $request->all();

        $validate = $this->validate($data, [
            'nome' => 'required|string|max:100',
            'codigo' => 'required|string|max:13',
            'preco' => 'required|float',
            'estoque' => 'required|float',
            'tipo' => 'required|string',
            'quant_entrada' => 'required|float',
            'quant_saida' => 'required|float',
            'grupo_produto_id' => 'required|int',
            'icms_id' => 'required|int',
            'ipi_id' => 'required|int',
            'pis_id' => 'required|int',
            'cofins_id' => 'required|int',
            'cfop' => 'required|int',
            'ncm' => 'int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $produto = $this->produtoRepository->update($data, $produto->id);

        if(is_null($produto)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Produto atualizado com sucesso',
            'data' => ProdutoTransformer::transform($produto)
        ], 201);
    }

    public function destroy(Request $request, string $uuid){
        $produto = $this->produtoRepository->findBy('uuid', $uuid);

        if(is_null($produto)){
            return $this->respJson([
                'message' => 'Produto não encontrado'
            ], 422);
        }

        $produto = $this->produtoRepository->delete($produto->id);

        if(is_null($produto)){
            return $this->respJson([
                'message' => 'Não foi possível deletar produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Produto deletado com sucesso',
        ], 201);
    }
}
