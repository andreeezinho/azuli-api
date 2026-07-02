<?php

namespace App\Http\Transformer\Cliente;

use App\Domain\Models\Cliente\Cliente;
use App\Http\Transformer\Endereco\EnderecoTransformer;

class ClienteTransformer {

    protected $enderecoTransformer;

    public function __construct(){
        $this->enderecoTransformer = new EnderecoTransformer();
    }

    public function transform(Cliente $data) : array {
        return [
            'uuid' => $data->uuid,
            'nome' => $data->nome,
            'email' => $data->email,
            'documento' => $data->documento,
            'telefone' => $data->telefone,
            'ie_rg' => $data->ie_rg,
            'contribuinte' => $data->contribuinte,
            'endereco' => $this->enderecoTransformer->transform($data->endereco()),
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(Cliente $data) {
            return self::transform($data);
        }, $produtos);
    }

}