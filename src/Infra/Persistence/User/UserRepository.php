<?php

namespace App\Infra\Persistence\User;

use App\Domain\Models\User\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    public static $className = User::class;

    public function __construct() {
        parent::__construct();
        $this->model = new User();
    }

    public function login(string $email, string $senha){
        $sql = "SELECT * FROM 
                {$this->model->getTable()}
            WHERE
                email = :email
            AND
                ativo = 1
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'email' => $email
        ]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $user = $stmt->fetch();

        if($user && password_verify($senha, $user->senha)){
            unset($user->id);
            unset($user->senha);
            return $user;
        }

        return null;
    }

    public function create(array $data){
        if(empty($data['senha'])){
            $data['senha'] = 'senha123';
        }

        if(empty($data['icone'])){
            $data['icone'] = 'default.png';
        }

        return parent::create($data);
    }

}