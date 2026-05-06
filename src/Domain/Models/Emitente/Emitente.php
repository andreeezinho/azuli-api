<?php

namespace App\Domain\Models\Emitente;

use App\Domain\Models\Traits\ModelTrait;
use App\Domain\Models\Empresa\Empresa;
use App\Infra\Persistence\Empresa\EmpresaRepository;

class Emitente {

    use ModelTrait;

    public const TABLE = 'emitentes';

    public int $id;
    public ?string $uuid;
    public string $empresas_id;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function empresa(){
        return $this->belongsTo(EmpresaRepository::class, $this->empresas_id);
    }

    public function create(array $data) : Emitente {
        $emitente = new Emitente();
        $emitente->setFields($data);
        $emitente->uuid = $data['uuid'] ?? $this->generateUUID();
        return $emitente;
    }

}