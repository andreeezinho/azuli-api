<?php

namespace App\Http\Transformer\Emitente;

use App\Domain\Models\Emitente\Emitente;
use App\Http\Transformer\Empresa\EmpresaTransformer;

class EmitenteTransformer {

    protected $empresaTransformer;

    public function __construct(){
        $this->empresaTransformer = new EmpresaTransformer();
    }

    public function transform(Emitente $data) : array {
        return [
            'uuid' => $data->uuid,
            'empresa' => $this->empresaTransformer->transform($data->empresa()),
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(Emitente $data) {
            return self::transform($data);
        }, $produtos);
    }

}