<?php

namespace App\Config;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\User\UserRepository;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Infra\Persistence\RecuperarSenha\RecuperarSenhaRepository;

class DependencyProvider {

    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    public function register(){

        $this->container
            ->set(
                UserRepositoryInterface::class,
                new UserRepository()
            );

        $this->container
            ->set(
                RecuperarSenhaRepositoryInterface::class,
                new RecuperarSenhaRepository()
            );

    }

}