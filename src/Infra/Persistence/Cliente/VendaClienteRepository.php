<?php

namespace App\Infra\Persistence\Cliente;

use App\Domain\Models\Cliente\VendaCliente;
use App\Domain\Repositories\Cliente\VendaClienteRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class VendaClienteRepository extends BaseRepository implements VendaClienteRepositoryInterface {

    public static $className = VendaCliente::class;

    public function __construct() {
        parent::__construct();
        $this->model = new VendaCliente();
    }

    public function findClientInSale(int $vendas_id){
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->model->getTable() . " ORDER BY created_at ASC"
        );

        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetchAll();

        if(empty($result)){
            return null;
        }

        return $result;
    }

}