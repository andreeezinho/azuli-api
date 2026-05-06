<?php

namespace App\Http\Transformer\Empresa;

use App\Domain\Models\Empresa\Empresa;
use App\Http\Transformer\Endereco\EnderecoTransformer;

class EmpresaTransformer {

    protected $enderecoTransformer;

    public function __construct(){
        $this->enderecoTransformer = new EnderecoTransformer();
    }

    public function transform(Empresa $data) : array {
        return [
            'uuid' => $data->uuid,
            'razao_social' => $data->razao_social,
            'nome_fantasia' => $data->nome_fantasia,
            'documento' => $data->documento,
            'ie_rg' => $data->ie_rg,
            'num_serie_nfe' => $data->num_serie_nfe,
            'endereco' => $this->enderecoTransformer->transform($data->endereco()),
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(Empresa $data) {
            return self::transform($data);
        }, $produtos);
    }

}