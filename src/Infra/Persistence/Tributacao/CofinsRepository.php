<?php

namespace App\Infra\Persistence\Tributacao;

use App\Domain\Models\Tributacao\Cofins;
use App\Domain\Repositories\Tributacao\CofinsRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class CofinsRepository extends BaseRepository implements CofinsRepositoryInterface {

    public static $className = Cofins::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Cofins();
    }

}