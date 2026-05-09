<?php

namespace App\Domain\Models\NotaFiscal;

use App\Domain\Models\Traits\ModelTrait;
use App\Infra\Persistence\Destinatario\DestinatarioRepository;
use App\Infra\Persistence\Venda\VendaRepository;

class NotaFiscal {

    use ModelTrait;

    public const TABLE = 'nota_fiscal';

    public int $id;
    public ?string $uuid;
    public string $chave;
    public int $num_nf;
    public string $nat_op;
    public int $vendas_id;
    public ?int $destinatarios_id;
    public float $total;
    public string $xml_path;
    public int $num_evento;
    public string $situacao;
    public ?string $created_at;
    public ?string $updated_at;

    public function destinatario(){
        return $this->belongsTo(DestinatarioRepository::class, $this->destinatarios_id) ?? null;
    }

    public function venda(){
        return $this->belongsTo(VendaRepository::class, $this->vendas_id);
    }

    public function create(array $data) : NotaFiscal {
        $notaFiscal = new NotaFiscal();
        $notaFiscal->setFields($data);
        $notaFiscal->uuid = $data['uuid'] ?? $this->generateUUID();
        return $notaFiscal;
    }

}