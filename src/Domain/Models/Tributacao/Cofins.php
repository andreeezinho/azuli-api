<?php

namespace App\Domain\Models\Tributacao;

use App\Domain\Models\Traits\ModelTrait;

class Cofins {

    use ModelTrait;

    public const TABLE = 'cofins';

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

    public function create(array $data) : Cofins {
        $trib = new Cofins();
        $trib->setFields($data);
        $trib->uuid = $data['uuid'] ?? $this->generateUUID();
        return $trib;
    }

}