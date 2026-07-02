<?php

namespace App\Http\Transformer\Tributacao;

class TributacaoTransformer {

    public function transform(mixed $data) : array {
        return [
            'uuid' => $data->uuid ?? null,
            'codigo' => $data->codigo ?? 0.00,
            'tributacao' => $data->tributacao ?? 0.00,
            'valor' => $data->valor ?? 0.00,
            'ativo' => $data->ativo ?? null,
            'created_at' => $data->created_at ?? null,
            'updated_at' => $data->updated_at ?? null
        ];
    }

    public function transformArray(array $tributacoes) : array {
        return array_map(function(mixed $data) {
            return self::transform($data);
        }, $tributacoes);
    }

}