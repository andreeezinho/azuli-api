<?php

namespace App\Domain\Models\Produto;

use App\Domain\Models\Traits\ModelTrait;
use App\Infra\Persistence\Produto\ProdutoRepository;
use App\Infra\Persistence\Venda\VendaRepository;

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

    public function venda(){
        return $this->belongsTo(VendaRepository::class, $this->vendas_id);
    }

    public function produto(){
        return $this->belongsTo(ProdutoRepository::class, $this->produtos_id);
    }

    public function create(array $data) : VendaProduto {
        $vendaProduto = new VendaProduto();
        $vendaProduto->setFields($data);
        $vendaProduto->uuid = $data['uuid'] ?? $this->generateUUID();
        return $vendaProduto;
    }

}