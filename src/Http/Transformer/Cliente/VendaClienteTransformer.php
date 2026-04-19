<?php

namespace App\Http\Transformer\Cliente;

use App\Domain\Models\Cliente\VendaCliente;

class VendaClienteTransformer {

    public static function transform(?VendaCliente $data) : array {
        if(is_null($data)){
            return [];
        }
        return [
            'uuid' => $data->uuid,
            'uuidCliente' => $data->uuidCliente,
            'nome' => $data->nome,
            'email' => $data->email,
            'documento' => $data->documento,
            'telefone' => $data->telefone,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(?array $clientes) : array {
        if(is_null($clientes)){
            return [];
        }
        return array_map(function(VendaCliente $data) {
            return self::transform($data);
        }, $clientes);
    }

}