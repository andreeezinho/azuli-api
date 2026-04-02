<?php

namespace App\Infra\Persistence\Pagamento;

use App\Domain\Models\Pagamento\Pagamento;
use App\Domain\Repositories\Pagamento\PagamentoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class PagamentoRepository extends BaseRepository implements PagamentoRepositoryInterface {

    public static $className = Pagamento::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Pagamento();
    }

}