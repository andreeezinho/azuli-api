<?php

namespace App\Http\Transformer\Tributacao;

class TributacaoTransformer {

    public static function transform(mixed $data) : array {
        return [
            'uuid' => $data->uuid,
            'codigo' => $data->codigo,
            'tributacao' => $data->tributacao,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $tributacoes) : array {
        return array_map(function(mixed $data) {
            return self::transform($data);
        }, $tributacoes);
    }

}