<?php

namespace App\Http\Transformer\Pagamento;

class PagamentoTransformer {

    public function transform(mixed $data) : array {
        return [
            'uuid' => $data->uuid,
            'forma' => $data->forma,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $tributacoes) : array {
        return array_map(function(mixed $data) {
            return self::transform($data);
        }, $tributacoes);
    }

}