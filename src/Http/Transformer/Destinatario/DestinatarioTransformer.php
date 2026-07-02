<?php

namespace App\Http\Transformer\Destinatario;

use App\Domain\Models\Destinatario\Destinatario;
use App\Http\Transformer\Empresa\EmpresaTransformer;

class DestinatarioTransformer {

    protected $empresaTransformer;

    public function __construct(){
        $this->empresaTransformer = new EmpresaTransformer();
    }

    public function transform(Destinatario $data) : array {
        return [
            'uuid' => $data->uuid,
            'empresa' => $this->empresaTransformer->transform($data->empresa()),
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(Destinatario $data) {
            return self::transform($data);
        }, $produtos);
    }

}