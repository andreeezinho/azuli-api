<?php

namespace App\Infra\Persistence\Venda;

use App\Domain\Models\Venda\Venda;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class VendaRepository extends BaseRepository implements VendaRepositoryInterface {

    public static $className = Venda::class;

    public function __construct(){
        parent::__construct();
        $this->model = new Venda();
    }

    public function findLastUserSale(int $user_id){
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->model->getTable() . " 
                WHERE 
                    updated_at <= NOW() 
                AND 
                    situacao = 'em andamento'
                AND
                    usuarios_id = :usuarios_id
                ORDER BY 
                    updated_at DESC 
                LIMIT 1"
        );

        $stmt->execute([":usuarios_id" => $user_id]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetch();

        if(empty($result)){
            return null;
        }

        return $result;
    }
}