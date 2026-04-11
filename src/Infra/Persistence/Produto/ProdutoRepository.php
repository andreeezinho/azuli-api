<?php

namespace App\Infra\Persistence\Produto;

use App\Domain\Models\Produto\Produto;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class ProdutoRepository extends BaseRepository implements ProdutoRepositoryInterface {

    public static $className = Produto::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Produto();
    }

}