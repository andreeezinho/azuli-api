<?php

namespace App\Domain\Repositories\Tributacao;

interface PisRepositoryInterface {

    public function all(array $params);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function findBy(string $field, mixed $value);

}