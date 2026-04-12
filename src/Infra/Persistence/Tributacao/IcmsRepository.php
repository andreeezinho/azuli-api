<?php

namespace App\Infra\Persistence\Tributacao;

use App\Domain\Models\Tributacao\Icms;
use App\Domain\Repositories\Tributacao\IcmsRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class IcmsRepository extends BaseRepository implements IcmsRepositoryInterface {

    public static $className = Icms::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Icms();
    }

}