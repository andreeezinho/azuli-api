<?php

namespace App\Infra\Persistence\Produto;

use App\Domain\Models\Produto\VendaProduto;
use App\Domain\Repositories\Produto\VendaProdutoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class VendaProdutoRepository extends BaseRepository implements VendaProdutoRepositoryInterface {

    public static $className = VendaProduto::class;

    public function __construct() {
        parent::__construct();
        $this->model = new VendaProduto();
    }

    public function findProductsInSale(int $vendas_id){
        $stmt = $this->conn->prepare(
            "SELECT vp.*,
                p.uuid as uuidProduto, p.nome as nome, p.codigo as codigo, p.preco as preco, p.estoque as estoque, p.tipo as tipo, p.grupo_produto_id as grupo_produto_id, p.ativo as ativo
            FROM " . $this->model->getTable() . " vp
            JOIN vendas v
                ON vendas_id = v.id
            JOIN produtos p
                ON produtos_id = p.id
            WHERE
                v.id = :vendas_id
            ORDER BY 
                vp.created_at ASC
        ");

        $stmt->execute([":vendas_id" => $vendas_id]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetchAll();

        if(empty($result)){
            return null;
        }

        return $result;
    }

    public function findBySaleAndProduct(int $vendas_id, int $produtos_id){
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->model->getTable() . " WHERE vendas_id = :vendas_id AND produtos_id = :produtos_id"
        );

        $stmt->execute([
            ":vendas_id" => $vendas_id,
            ":produtos_id" => $produtos_id
        ]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetch();

        if(empty($result)){
            return null;
        }

        return $result;
    }

}