<?php

namespace App\Infra\Persistence\RecuperarSenha;

use App\Domain\Models\RecuperarSenha\RecuperarSenha;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class RecuperarSenhaRepository extends BaseRepository implements RecuperarSenhaRepositoryInterface {

    public static $className = RecuperarSenha::class;

    public function __construct() {
        parent::__construct();
        $this->model = new RecuperarSenha();
    }

    public function verifyCode(int $code, int $usuarios_id) : bool {
        $find = $this->findBy('usuarios_id', $usuarios_id);

        if(time() > strtotime($find->expires_at)){
            return false;
        }

        if($code !== $find->codigo){
            return false;
        }

        return true;
    }

}