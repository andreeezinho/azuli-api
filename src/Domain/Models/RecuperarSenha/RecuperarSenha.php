<?php

namespace App\Domain\Models\RecuperarSenha;

use App\Domain\Models\Traits\ModelTrait;
use App\Infra\Persistence\User\UserRepository;

class RecuperarSenha {

    use ModelTrait;

    public const TABLE = 'recuperar_senha';

    public int $id;
    public ?string $uuid;
    public int $usuarios_id;
    public int $codigo;
    public ?string $expires_at;
    public ?string $created_at;
    public ?string $updated_at;

    public function usuario(){
        return $this->belongsTo(UserRepository::class, $this->usuarios_id);
    }

    public function create(array $data) : RecuperarSenha {
        $recuperarSenha = new RecuperarSenha();
        $recuperarSenha->setFields($data);
        $recuperarSenha->uuid = $data['uuid'] ?? $this->generateUUID();
        return $recuperarSenha;
    } 

}