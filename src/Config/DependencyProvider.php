<?php

namespace App\Config;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\User\UserRepository;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Infra\Persistence\RecuperarSenha\RecuperarSenhaRepository;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Infra\Persistence\Produto\ProdutoRepository;

//tributacao
use App\Domain\Repositories\Tributacao\IcmsRepositoryInterface;
use App\Infra\Persistence\Tributacao\IcmsRepository;
use App\Domain\Repositories\Tributacao\IpiRepositoryInterface;
use App\Infra\Persistence\Tributacao\IpiRepository;
use App\Domain\Repositories\Tributacao\PisRepositoryInterface;
use App\Infra\Persistence\Tributacao\PisRepository;
use App\Domain\Repositories\Tributacao\CofinsRepositoryInterface;
use App\Infra\Persistence\Tributacao\CofinsRepository;

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

        $this->container
            ->set(
                ProdutoRepositoryInterface::class,
                new ProdutoRepository()
            );
        
        $this->container
            ->set(
                IcmsRepositoryInterface::class,
                new IcmsRepository()
            );

        $this->container
            ->set(
                IpiRepositoryInterface::class,
                new IpiRepository()
            );

        $this->container
            ->set(
                PisRepositoryInterface::class,
                new PisRepository()
            );

        $this->container
            ->set(
                CofinsRepositoryInterface::class,
                new CofinsRepository()
            );

    }

}