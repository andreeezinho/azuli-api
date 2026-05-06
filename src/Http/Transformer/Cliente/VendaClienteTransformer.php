<?php

namespace App\Http\Transformer\Cliente;

use App\Domain\Models\Cliente\VendaCliente;
use App\Http\Transformer\Venda\VendaTransformer;

class VendaClienteTransformer {

    protected $vendaTransformer;
    protected $clienteTransformer;

    public function __construct(){
        $this->vendaTransformer = new VendaTransformer();
        $this->clienteTransformer = new ClienteTransformer();
    }

    public function transform(?VendaCliente $data) : array {
        if(is_null($data)){
            return [];
        }
        return [
            'uuid' => $data->uuid,
            'venda' => $this->vendaTransformer->transform($data->venda()),
            'cliente' => $this->clienteTransformer->transform($data->cliente()),
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(?array $clientes) : array {
        if(is_null($clientes)){
            return [];
        }
        return array_map(function(VendaCliente $data) {
            return self::transform($data);
        }, $clientes);
    }

}