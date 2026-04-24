<?php

namespace App\Domain\Models\Tributacao;

use App\Domain\Models\Traits\ModelTrait;

class Pis {

    use ModelTrait;

    public const TABLE = 'pis';

    public int $id;
    public ?string $uuid;
    public ?string $tipo;
    public string $codigo;
    public float $tributacao;
    public float $valor;
    public float $vbc;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Pis {
        $trib = new Pis();
        $trib->setFields($data);
        $trib->uuid = $data['uuid'] ?? $this->generateUUID();
        return $trib;
    }

}