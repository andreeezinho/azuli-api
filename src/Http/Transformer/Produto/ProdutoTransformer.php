<?php

namespace App\Http\Transformer\Produto;

use App\Domain\Models\Produto\Produto;
use App\Http\Transformer\GrupoProduto\GrupoProdutoTransformer;
use App\Http\Transformer\Tributacao\TributacaoTransformer;

class ProdutoTransformer {

    protected $tributacaoTransformer;
    protected $grupoProdutoTransformer;

    public function __construct(){
        $this->tributacaoTransformer = new TributacaoTransformer();
        $this->grupoProdutoTransformer = new GrupoProdutoTransformer();
    }

    public function transform(Produto $data) : array {
        return [
            'uuid' => $data->uuid,
            'nome' => $data->nome,
            'codigo' => $data->codigo,
            'preco' => $data->preco,
            'estoque' => $data->estoque,
            'tipo' => $data->tipo,
            'quant_entrada' => $data->quant_entrada,
            'quant_saida' => $data->quant_saida,
            'grupoProduto' => $this->grupoProdutoTransformer->transform($data->grupoProduto()),
            'icms' => $this->tributacaoTransformer->transform($data->icms()),
            'ipi' => $this->tributacaoTransformer->transform($data->ipi()),
            'pis' => $this->tributacaoTransformer->transform($data->pis()),
            'cofins' => $this->tributacaoTransformer->transform($data->cofins()),
            'cfop' => $data->cfop,
            'ncm' => $data->ncm,
            'cest' => $data->cest,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(Produto $data) {
            return self::transform($data);
        }, $produtos);
    }

}