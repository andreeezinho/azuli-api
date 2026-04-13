<?php

namespace App\Http\Transformer\Venda;

use App\Domain\Models\Venda\Venda;

class VendaTransformer {

    public static function transform(Venda $data) : array {
        return [
            'uuid' => $data->uuid,
            'desconto' => $data->desconto,
            'total' => $data->total,
            'troco' => $data->troco,
            'usuarios_id' => $data->usuarios_id,
            'situacao' => $data->situacao,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(Venda $data) {
            return self::transform($data);
        }, $produtos);
    }

}