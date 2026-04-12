<?php

namespace App\Infra\Persistence\Tributacao;

use App\Domain\Models\Tributacao\Pis;
use App\Domain\Repositories\Tributacao\PisRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class PisRepository extends BaseRepository implements PisRepositoryInterface {

    public static $className = Pis::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Pis();
    }

}