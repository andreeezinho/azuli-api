<?php

namespace App\Infra\Persistence\Produto;

use App\Domain\Models\Produto\VendaProduto;
use App\Domain\Repositories\Produto\VendaProdutoRepositoryInterface;
use App\Infra\Persistence\Produto\ProdutoRepository;
use App\Infra\Persistence\BaseRepository;
use App\Infra\Services\Log\LogService;

class VendaProdutoRepository extends BaseRepository implements VendaProdutoRepositoryInterface {

    public static $className = VendaProduto::class;
    protected $produtoRepository;

    public function __construct() {
        parent::__construct();
        $this->model = new VendaProduto();
        $this->produtoRepository = new ProdutoRepository();
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

    public function deleteAllProductsInSale(int $vendas_id){
        $sql = "DELETE FROM " . $this->model->getTable() . "
            WHERE
                vendas_id = :vendas_id
        ";

        $stmt = $this->conn->prepare($sql);

        $delete = $stmt->execute([
            ':vendas_id' => $vendas_id
        ]);

        return $delete;
    }

    public function subtractProductsStock(array $produtos){
        try {
            foreach($produtos as $prod){
                $quant = $prod->quantidade;

                $produto = $this->produtoRepository->findBy('id', $prod->produtos_id);

                $subtract = (float)$produto->estoque - (float)$quant;

                if($subtract < 0){
                    return null;
                }

                $update = $this->produtoRepository->update(['estoque' => $subtract], $produto->id);

                if(is_null($update)){
                    return null;
                }

                continue;
            }

            return true;
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

}