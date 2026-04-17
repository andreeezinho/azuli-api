<?php

namespace App\Http\Transformer\Endereco;

use App\Domain\Models\Endereco\Endereco;

class EnderecoTransformer {

    public static function transform(Endereco $data) : array {
        return [
            'uuid' => $data->uuid,
            'cep' => $data->cep,
            'uf' => $data->uf,
            'codigo' => $data->codigo,
            'cidade' => $data->cidade,
            'rua' => $data->rua,
            'bairro' => $data->bairro,
            'numero' => $data->numero,
            'complemento' => $data->complemento,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(Endereco $data) {
            return self::transform($data);
        }, $produtos);
    }

}