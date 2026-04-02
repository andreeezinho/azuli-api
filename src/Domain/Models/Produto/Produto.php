<?php

namespace App\Domain\Models\Produto;

use App\Domain\Models\Traits\ModelTrait;

class Produto {

    use ModelTrait;

    public const TABLE = 'produtos';

    public int $id;
    public ?string $uuid;
    public string $nome;
    public string $codigo;
    public float $preco;
    public float $estoque;
    public string $tipo;
    public float $quant_entrada;
    public float $quant_saida;
    public int $grupo_produto_id;
    public int $icms_id;
    public int $ipi_id;
    public int $pis_id;
    public int $cofins_id;
    public int $cfop;
    public ?int $ncm;
    public ?int $cest;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Produto {
        $produto = new Produto();
        $produto->setFields($data);
        $produto->uuid = $data['uuid'] ?? $this->generateUUID();
        return $produto;
    }

}