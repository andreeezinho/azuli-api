<?php

namespace App\Http\Transformer\NotaFiscal;

use App\Domain\Models\NotaFiscal\NotaFiscalEntrada;
use App\Http\Transformer\Emitente\EmitenteTransformer;

class NotaFiscalEntradaTransformer {

    protected $emitenteTransformer;

    public function __construct(){
        $this->emitenteTransformer = new EmitenteTransformer();
    }

    public function transform(NotaFiscalEntrada $data) : array {
        return [
            'uuid' => $data->uuid,
            'chave' => $data->chave,
            'num_nf' => $data->num_nf,
            'nat_op' => $data->nat_op,
            'gravada' => $data->gravada,
            'data_emissao' => $data->data_emissao,
            'emitentes' => $this->emitenteTransformer->transform($data->emitente()),
            'total' => $data->total,
            'xml_path' => $data->xml_path,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(NotaFiscalEntrada $data) {
            return self::transform($data);
        }, $produtos);
    }

}