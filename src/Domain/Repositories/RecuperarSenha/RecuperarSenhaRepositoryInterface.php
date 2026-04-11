<?php

namespace App\Domain\Repositories\RecuperarSenha;

interface RecuperarSenhaRepositoryInterface {

    public function create(array $data);

    public function verifyCode(int $code, int $usuarios_id) : bool;

    public function delete(int $id);

    public function findBy(string $field, mixed $value);

}