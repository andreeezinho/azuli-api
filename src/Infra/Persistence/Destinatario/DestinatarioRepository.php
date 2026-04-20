<?php

namespace App\Infra\Persistence\Destinatario;

use App\Domain\Models\Destinatario\Destinatario;
use App\Domain\Repositories\Destinatario\DestinatarioRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class DestinatarioRepository extends BaseRepository implements DestinatarioRepositoryInterface {

    public static $className = Destinatario::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Destinatario();
    }

}