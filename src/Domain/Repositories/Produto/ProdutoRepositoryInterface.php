<?php

namespace App\Domain\Repositories\Produto;

interface ProdutoRepositoryInterface {

    public function all(array $params);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function findBy(string $field, mixed $value);

    public function registerProductsFromInvoice(array $products);

}