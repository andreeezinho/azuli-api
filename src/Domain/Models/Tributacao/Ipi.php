<?php

namespace App\Domain\Models\Tributacao;

use App\Domain\Models\Traits\ModelTrait;

class Ipi {

    use ModelTrait;

    public const TABLE = 'ipi';

    public int $id;
    public ?string $uuid;
    public int $cEnq;
    public string $codigo;
    public float $tributacao;
    public float $valor;
    public float $vbc;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Ipi {
        $trib = new Ipi();
        $trib->setFields($data);
        $trib->uuid = $data['uuid'] ?? $this->generateUUID();
        return $trib;
    }

}