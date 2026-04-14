<?php

namespace App\Http\Transformer\Produto;

use App\Domain\Models\Produto\VendaProduto;

class VendaProdutoTransformer {

    public static function transform(VendaProduto $data) : array {
        return [
            'uuid' => $data->uuid,
            'uuidProduto' => $data->uuidProduto,
            'nome' => $data->nome,
            'codigo' => $data->codigo,
            'preco' => $data->preco,
            'quantidade' => $data->quantidade,
            'estoque' => $data->estoque,
            'tipo' => $data->tipo,
            'grupo_produto_id' => $data->grupo_produto_id,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(VendaProduto $data) {
            return self::transform($data);
        }, $produtos);
    }

}