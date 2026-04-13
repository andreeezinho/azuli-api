<?php

namespace App\Http\Transformer\GrupoProduto;

use App\Domain\Models\GrupoProduto\GrupoProduto;

class GrupoProdutoTransformer {

    public static function transform(GrupoProduto $data) : array {
        return [
            'uuid' => $data->uuid,
            'nome' => $data->nome,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(GrupoProduto $data) {
            return self::transform($data);
        }, $produtos);
    }

}