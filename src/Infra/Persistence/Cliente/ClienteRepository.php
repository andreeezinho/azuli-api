<?php

namespace App\Infra\Persistence\Cliente;

use App\Domain\Models\Cliente\Cliente;
use App\Domain\Repositories\Cliente\ClienteRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class ClienteRepository extends BaseRepository implements ClienteRepositoryInterface {

    public static $className = Cliente::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Cliente();
    }

}