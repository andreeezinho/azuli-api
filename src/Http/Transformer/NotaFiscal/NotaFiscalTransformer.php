<?php

namespace App\Http\Transformer\NotaFiscal;

use App\Domain\Models\NotaFiscal\NotaFiscal;
use App\Http\Transformer\Destinatario\DestinatarioTransformer;

class NotaFiscalTransformer {

    protected $destinatarioTransformer;

    public function __construct(){
        $this->destinatarioTransformer = new DestinatarioTransformer();
    }

    public function transform(NotaFiscal $data) : array {
        return [
            'uuid' => $data->uuid,
            'chave' => $data->chave,
            'num_nf' => $data->num_nf,
            'nat_op' => $data->nat_op,
            'situacao' => $data->situacao,
            'destinatario' => $this->destinatarioTransformer->transform($data->destinatario()) ?? null,
            'total' => $data->total,
            'xml_path' => $data->xml_path,
            'xml_evento_path' => $data->xml_evento_path,
            'num_evento' => $data->num_evento,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
    }

    public function transformArray(array $produtos) : array {
        return array_map(function(NotaFiscal $data) {
            return self::transform($data);
        }, $produtos);
    }

}