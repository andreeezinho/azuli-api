<?php

namespace App\Domain\Models\Pagamento;

use App\Domain\Models\Traits\ModelTrait;

class VendaPagamento {

    use ModelTrait;

    public const TABLE = 'venda_pagamento';

    public int $id;
    public ?string $uuid;
    public float $valor;
    public int $vendas_id;
    public int $pagamento_id;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : VendaPagamento {
        $vendaPagamento = new VendaPagamento();
        $vendaPagamento->setFields($data);
        $vendaPagamento->uuid = $data['uuid'] ?? $this->generateUUID();
        return $vendaPagamento;
    }

}