<?php

namespace App\Domain\Models\Cliente;

use App\Domain\Models\Traits\ModelTrait;

class Cliente { 

    use ModelTrait;

    public const TABLE = 'clientes';

    public int $id;
    public ?string $uuid;
    public string $nome;
    public ?string $email;
    public ?string $documento;
    public string $telefone;
    public ?string $ie_rg;
    public int $contribuinte;
    public int $enderecos_id;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Cliente {
        $cliente = new Cliente();
        $cliente->setFields($data);
        $cliente->uuid = $data['uuid'] ?? $this->generateUUID();
        return $cliente;
    }

}