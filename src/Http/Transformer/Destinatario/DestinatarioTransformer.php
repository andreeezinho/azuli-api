<?php

namespace App\Http\Transformer\Destinatario;

use App\Domain\Models\Destinatario\Destinatario;

class DestinatarioTransformer {

    public static function transform(Destinatario $data) : array {
        return [
            'uuid' => $data->uuid,
            'razao_social' => $data->razao_social,
            'nome_fantasia' => $data->nome_fantasia,
            'documento' => $data->documento,
            'ie_rg' => $data->ie_rg,
            'num_serie_nfe' => $data->num_serie_nfe,
            'enderecos_id' => $data->enderecos_id,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $produtos) : array {
        return array_map(function(Destinatario $data) {
            return self::transform($data);
        }, $produtos);
    }

}