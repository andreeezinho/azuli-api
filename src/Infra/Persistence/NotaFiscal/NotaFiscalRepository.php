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

    public function getLastNfeNumber(){
        $stmt = $this->conn->prepare(
            "SELECT num_nf FROM " . $this->model->getTable() . " ORDER BY id DESC LIMIT 1"
        );

        $stmt->execute([]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetch();

        if(empty($result)){
            return 0;
        }

        return $result->num_nf;
    }

}