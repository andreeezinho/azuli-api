<?php

namespace App\Infra\Persistence\Endereco;

use App\Domain\Models\Endereco\Endereco;
use App\Domain\Repositories\Endereco\EnderecoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class EnderecoRepository extends BaseRepository implements EnderecoRepositoryInterface {

    public static $className = Endereco::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Endereco();
    }

}