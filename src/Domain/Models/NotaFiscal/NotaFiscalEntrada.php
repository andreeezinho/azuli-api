<?php

namespace App\Domain\Models\NotaFiscal;

use App\Domain\Models\Traits\ModelTrait;
use App\Infra\Persistence\Emitente\EmitenteRepository;

class NotaFiscalEntrada {

    use ModelTrait;

    public const TABLE = 'nota_fiscal_entrada';

    public int $id;
    public ?string $uuid;
    public string $chave;
    public int $num_nf;
    public string $nat_op;
    public int $gravada;
    public string $data_emissao;
    public int $emitentes_id;
    public float $total;
    public string $xml_path;
    public ?string $created_at;
    public ?string $updated_at;

    public function emitente(){
        return $this->belongsTo(EmitenteRepository::class, $this->emitentes_id);
    }

    public function create(array $data) : NotaFiscalEntrada {
        $notaFiscal = new NotaFiscalEntrada();
        $notaFiscal->setFields($data);
        $notaFiscal->uuid = $data['uuid'] ?? $this->generateUUID();
        return $notaFiscal;
    }

}