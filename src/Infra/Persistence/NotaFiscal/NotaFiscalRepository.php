<?php

namespace App\Infra\Persistence\NotaFiscal;

use App\Domain\Models\NotaFiscal\NotaFiscal;
use App\Domain\Repositories\NotaFiscal\NotaFiscalRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class NotaFiscalRepository extends BaseRepository implements NotaFiscalRepositoryInterface {

    public static $className = NotaFiscal::class;

    public function __construct() {
        parent::__construct();
        $this->model = new NotaFiscal();
    }

}