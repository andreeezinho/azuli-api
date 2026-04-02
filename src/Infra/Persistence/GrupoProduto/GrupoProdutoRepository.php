<?php

namespace App\Infra\Persistence\GrupoProduto;

use App\Domain\Models\GrupoProduto\GrupoProduto;
use App\Domain\Repositories\GrupoProduto\GrupoProdutoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class GrupoProdutoRepository extends BaseRepository implements GrupoProdutoRepositoryInterface {

    public static $className = GrupoProduto::class;

    public function __construct() {
        parent::__construct();
        $this->model = new GrupoProduto();
    }

}