<?php

namespace App\Http\Transformer\Cliente;

use App\Domain\Models\Cliente\Cliente;

class ClienteTransformer {

    public static function transform(Cliente $data) : array {
        return [
            'uuid' => $data->uuid,
            'nome' => $data->nome,
            'email' => $data->email,
            'documento' => $data->documento,
            'telefone' => $data->telefone,
            'ie_rg' => $data->ie_rg,
            'contribuinte' => $data->contribuinte,
            'enderecos_id' => $data->enderecos_id,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(Cliente $data) {
            return self::transform($data);
        }, $produtos);
    }

}