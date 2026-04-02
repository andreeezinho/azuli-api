<?php

namespace App\Domain\Models\Venda;

use App\Domain\Models\Traits\ModelTrait;

class Venda {

    use ModelTrait;

    public const TABLE = 'vendas';

    public int $id;
    public ?string $uuid;
    public int $desconto;
    public ?float $total;
    public ?float $troco;
    public int $usuarios_id;
    public string $situacao;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Venda {
        $venda = new Venda();
        $venda->setFields($data);
        $venda->uuid = $data['uuid'] ?? $this->generateUUID();
        return $venda;
    }

}