<?php

namespace App\Domain\Models\Cliente;

use App\Domain\Models\Traits\ModelTrait;
use App\Infra\Persistence\Cliente\ClienteRepository;
use App\Infra\Persistence\Venda\VendaRepository;

class VendaCliente {

    use ModelTrait;

    public const TABLE = 'venda_cliente';

    public int $id;
    public ?string $uuid;
    public int $clientes_id;
    public int $vendas_id;
    public ?string $created_at;
    public ?string $updated_at;

    public function cliente(){
        return $this->belongsTo(ClienteRepository::class, $this->clientes_id);
    }

    public function venda(){
        return $this->belongsTo(VendaRepository::class, $this->vendas_id);
    }

    public function create(array $data) : VendaCliente {
        $vendaCliente = new VendaCliente();
        $vendaCliente->setFields($data);
        $vendaCliente->uuid = $data['uuid'] ?? $this->generateUUID();
        return $vendaCliente;
    }

}