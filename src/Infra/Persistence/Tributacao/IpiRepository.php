<?php

namespace App\Infra\Persistence\Ipi;

use App\Domain\Models\Tributacao\Ipi;
use App\Domain\Repositories\Tributacao\IpiRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class IpiRepository extends BaseRepository implements IpiRepositoryInterface {

    public static $className = Ipi::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Ipi();
    }

}