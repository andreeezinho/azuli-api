<?php

namespace App\Domain\Models\GrupoProduto;

use App\Domain\Models\Traits\ModelTrait;

class GrupoProduto {

    use ModelTrait;

    public const TABLE = 'grupo_produto';

    public int $id;
    public ?string $uuid;
    public string $nome;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : GrupoProduto {
        $grupoProduto = new GrupoProduto();
        $grupoProduto->setFields($data);
        $grupoProduto->uuid = $data['uuid'] ?? $this->generateUUID();
        return $grupoProduto;
    }

}