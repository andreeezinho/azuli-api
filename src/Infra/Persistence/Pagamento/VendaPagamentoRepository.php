<?php

namespace App\Infra\Persistence\Pagamento;

use App\Domain\Models\Pagamento\VendaPagamento;
use App\Domain\Repositories\Pagamento\VendaPagamentoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class VendaPagamentoRepository extends BaseRepository implements VendaPagamentoRepositoryInterface {

    public static $className = VendaPagamento::class;

    public function __construct() {
        parent::__construct();
        $this->model = new VendaPagamento();
    }

}