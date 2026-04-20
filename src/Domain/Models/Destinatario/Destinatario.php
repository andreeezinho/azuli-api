<?php

namespace App\Domain\Models\Destinatario;

use App\Domain\Models\Traits\ModelTrait;

class Destinatario {

    use ModelTrait;

    public const TABLE = 'destinatarios';

    public int $id;
    public ?string $uuid;
    public string $empresas_id;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Destinatario {
        $destinatario = new Destinatario();
        $destinatario->setFields($data);
        $destinatario->uuid = $data['uuid'] ?? $this->generateUUID();
        return $destinatario;
    }

}