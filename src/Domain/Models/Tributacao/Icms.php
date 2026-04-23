<?php

namespace App\Domain\Models\Tributacao;

use App\Domain\Models\Traits\ModelTrait;

class Icms {

    use ModelTrait;

    public const TABLE = 'icms';

    public int $id;
    public ?string $uuid;
    public int $orig;
    public string $tipo;
    public string $codigo;
    public float $tributacao;
    public float $valor;
    public float $vbc;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Icms {
        $trib = new Icms();
        $trib->setFields($data);
        $trib->uuid = $data['uuid'] ?? $this->generateUUID();
        return $trib;
    }

}