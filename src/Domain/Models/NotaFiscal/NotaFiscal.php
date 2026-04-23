<?php

namespace App\Domain\Models\NotaFiscal;

use App\Domain\Models\Traits\ModelTrait;

class NotaFiscal {

    use ModelTrait;

    public const TABLE = 'nota_fiscal';

    public int $id;
    public ?string $uuid;
    public string $chave;
    public int $num_nf;
    public string $nat_op;
    public int $vendas_id;
    public int $destinatarios_id;
    public float $total;
    public string $xml_path;
    public string $situacao;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : NotaFiscal {
        $notaFiscal = new NotaFiscal();
        $notaFiscal->setFields($data);
        $notaFiscal->uuid = $data['uuid'] ?? $this->generateUUID();
        return $notaFiscal;
    }

}