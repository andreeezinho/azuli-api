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
            "SELECT vc.*,
                c.uuid as uuidCliente, c.nome as nome, c.email as email, c.telefone as telefone, c.nome as nome, c.documento as documento, c.ativo as ativo
            FROM " . $this->model->getTable() . " vc
            JOIN vendas v
                ON vendas_id = v.id
            JOIN clientes c
                ON clientes_id =c.id
            WHERE
                v.id = :vendas_id
            ORDER BY 
                vc.created_at ASC
        ");

        $stmt->execute([":vendas_id" => $vendas_id]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetchAll();

        if(empty($result)){
            return null;
        }

        return $result;
    }

}