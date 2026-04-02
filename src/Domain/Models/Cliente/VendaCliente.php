<?php

namespace App\Domain\Models\Cliente;

use App\Domain\Models\Traits\ModelTrait;

class VendaCliente {

    use ModelTrait;

    public const TABLE = 'venda_cliente';

    public int $id;
    public ?string $uuid;
    public int $clientes_id;
    public int $venda_id;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : VendaCliente {
        $vendaCliente = new VendaCliente();
        $vendaCliente->setFields($data);
        $vendaCliente->uuid = $data['uuid'] ?? $this->generateUUID();
        return $vendaCliente;
    }

}