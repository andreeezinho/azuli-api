<?php

namespace App\Infra\Persistence\NotaFiscal;

use App\Domain\Models\NotaFiscal\NotaFiscalEntrada;
use App\Domain\Repositories\NotaFiscal\NotaFiscalEntradaRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class NotaFiscalEntradaRepository extends BaseRepository implements NotaFiscalEntradaRepositoryInterface {

    public static $className = NotaFiscalEntrada::class;

    public function __construct() {
        parent::__construct();
        $this->model = new NotaFiscalEntrada();
    }

}