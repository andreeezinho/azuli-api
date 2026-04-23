<?php

namespace App\Domain\Models\Empresa;

use App\Domain\Models\Traits\ModelTrait;

class Empresa {

    use ModelTrait;

    public const TABLE = 'empresas';

    public int $id;
    public ?string $uuid;
    public string $razao_social;
    public ?string $nome_fantasia;
    public string $documento;
    public string $ie_rg;
    public int $num_serie_nfe;
    public int $enderecos_id;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Empresa {
        $empresa = new Empresa();
        $empresa->setFields($data);
        $empresa->uuid = $data['uuid'] ?? $this->generateUUID();
        return $empresa;
    }

}