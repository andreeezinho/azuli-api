<?php

namespace App\Domain\Models\User;

use App\Domain\Models\Traits\ModelTrait;

class User {

    use ModelTrait;

    public const TABLE = 'usuarios';

    public int $id;
    public ?string $uuid;
    public string $usuario;
    public string $nome;
    public string $email;
    public ?string $cpf;
    public ?string $telefone;
    public ?string $senha;
    // public string $funcao;
    public int $ativo;
    public int $is_admin;
    public ?string $icone;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : User {
        $user = new User();
        $user->setFields($data);
        $user->uuid = $data['uuid'] ?? $this->generateUUID();
        return $user;
    }   

}