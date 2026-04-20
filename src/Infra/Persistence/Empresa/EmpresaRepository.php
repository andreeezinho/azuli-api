<?php

namespace App\Infra\Persistence\Empresa;

use App\Domain\Models\Empresa\Empresa;
use App\Domain\Repositories\Empresa\EmpresaRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class EmpresaRepository extends BaseRepository implements EmpresaRepositoryInterface {

    public static $className = Empresa::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Empresa();
    }

}