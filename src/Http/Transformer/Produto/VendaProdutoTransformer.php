<?php

namespace App\Http\Transformer\Produto;

use App\Domain\Models\Produto\VendaProduto;

class VendaProdutoTransformer {

    protected $produtoTransformer;

    public function __construct(){
        $this->produtoTransformer = new ProdutoTransformer();
    }

    public function transform(?VendaProduto $data) : array {
        if(is_null($data)){
            return [];
        }

        return [
            'uuid' => $data->uuid,
            'quantidade' => $data->quantidade,
            'produto' => $this->produtoTransformer->transform($data->produto()),
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(?array $produtos) : array {
        if(is_null($produtos)){
            return [];
        }

        return array_map(function(VendaProduto $data) {
            return self::transform($data);
        }, $produtos);
    }

}