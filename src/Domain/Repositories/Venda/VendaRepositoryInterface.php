<?php

namespace App\Domain\Repositories\Venda;

interface VendaRepositoryInterface {

    public function all(array $params);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function findLastUserSale(int $user_id);

    public function findBy(string $field, mixed $value);

}