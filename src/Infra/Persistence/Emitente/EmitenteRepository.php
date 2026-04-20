<?php

namespace App\Infra\Persistence\Emitente;

use App\Domain\Models\Emitente\Emitente;
use App\Domain\Repositories\Emitente\EmitenteRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class EmitenteRepository extends BaseRepository implements EmitenteRepositoryInterface {

    public static $className = Emitente::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Emitente();
    }

}