<?php

namespace App\Http\Transformer\User;

use App\Domain\Models\User\User;

class UserTransformer {

    public static function transform(User $data) : array {
        return [
            'uuid' => $data->uuid,
            'usuario' => $data->usuario,
            'nome' => $data->nome,
            'email' => $data->email,
            'cpf' => $data->cpf,
            'telefone' => $data->telefone,
            'ativo' => $data->ativo,
            'is_admin' => $data->is_admin,
            'icone' => $data->icone,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public static function transformArray(array $users) : array {
        return array_map(function(User $data) {
            return self::transform($data);
        }, $users);
    }

}