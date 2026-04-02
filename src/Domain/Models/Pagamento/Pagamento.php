<?php

namespace App\Domain\Models\Pagamento;

use App\Domain\Models\Traits\ModelTrait;

class Pagamento {

    use ModelTrait;

    public const TABLE = 'pagamentos';

    public int $id;
    public ?string $uuid;
    public string $forma;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Pagamento {
        $pagamento = new Pagamento();
        $pagamento->setFields($data);
        $pagamento->uuid = $data['uuid'] ?? $this->generateUUID();
        return $pagamento;
    }

}