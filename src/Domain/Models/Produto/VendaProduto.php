<?php

namespace App\Domain\Models\Produto;

use App\Domain\Models\Traits\ModelTrait;

class VendaProduto {

    use ModelTrait;

    public const TABLE = 'venda_produto';

    public int $id;
    public ?string $uuid;
    public float $quantidade;
    public int $vendas_id;
    public int $produtos_id;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : VendaProduto {
        $vendaProduto = new VendaProduto();
        $vendaProduto->setFields($data);
        $vendaProduto->uuid = $data['uuid'] ?? $this->generateUUID();
        return $vendaProduto;
    }

}