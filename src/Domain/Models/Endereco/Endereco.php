<?php

namespace App\Domain\Models\Endereco;

use App\Domain\Models\Traits\ModelTrait;

class Endereco {

    use ModelTrait;

    public int $id;
    public ?string $uuid;
    public string $cep;
    public string $uf;
    public ?int $codigo;
    public string $cidade;
    public string $rua;
    public string $bairro;
    public string $numero;
    public ?string $complemento;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Endereco {
        $endereco = new Endereco();
        $endereco->setFields($data);
        $endereco->uuid = $data['uuid'] ?? $this->generateUUID();
        return $endereco;
    }

}