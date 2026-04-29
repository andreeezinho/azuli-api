<?php

namespace App\Domain\Repositories\Produto;

interface VendaProdutoRepositoryInterface {

    public function all(array $params);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function findBy(string $field, mixed $value);

    public function findProductsInSale(int $vendas_id);

    public function findBySaleAndProduct(int $vendas_id, int $produtos_id);

    public function deleteAllProductsInSale(int $vendas_id);

    public function subtractProductsStock(array $produtos);

}