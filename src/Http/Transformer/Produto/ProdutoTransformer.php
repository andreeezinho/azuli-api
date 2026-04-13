<?php

namespace App\Http\Transformer\Produto;

use App\Domain\Models\Produto\Produto;

class ProdutoTransformer {

    public static function transform(Produto $data) : array {
        return [
            'uuid' => $data->uuid,
            'nome' => $data->nome,
            'codigo' => $data->codigo,
            'preco' => $data->preco,
            'estoque' => $data->estoque,
            'tipo' => $data->tipo,
            'quant_entrada' => $data->quant_entrada,
            'quant_saida' => $data->quant_saida,
            'grupo_produto_id' => $data->grupo_produto_id,
            'icms_id' => $data->icms_id,
            'ipi_id' => $data->ipi_id,
            'pis_id' => $data->pis_id,
            'cofins_id' => $data->cofins_id,
            'cfop' => $data->cfop,
            'ncm' => $data->ncm,
            'cest' => $data->cest,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(Produto $data) {
            return self::transform($data);
        }, $produtos);
    }

}