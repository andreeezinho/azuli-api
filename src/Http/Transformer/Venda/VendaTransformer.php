<?php

namespace App\Http\Transformer\Venda;

use App\Domain\Models\Venda\Venda;
use App\Http\Transformer\User\UserTransformer;

class VendaTransformer {
    
    protected $userTransformer;

    public function __construct(){
        $this->userTransformer = new UserTransformer();   
    }

    public function transform(Venda $data) : array {
        return [
            'uuid' => $data->uuid,
            'desconto' => $data->desconto,
            'total' => $data->total,
            'troco' => $data->troco,
            'usuario' => $this->userTransformer->transform($data->usuario()),
            'situacao' => $data->situacao,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(Venda $data) {
            return self::transform($data);
        }, $produtos);
    }

}