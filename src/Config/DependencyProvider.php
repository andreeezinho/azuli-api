<?php

namespace App\Config;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\User\UserRepository;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Infra\Persistence\RecuperarSenha\RecuperarSenhaRepository;
use App\Domain\Repositories\GrupoProduto\GrupoProdutoRepositoryInterface;
use App\Infra\Persistence\GrupoProduto\GrupoProdutoRepository;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Infra\Persistence\Produto\ProdutoRepository;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Infra\Persistence\Venda\VendaRepository;
use App\Domain\Repositories\Produto\VendaProdutoRepositoryInterface;
use App\Infra\Persistence\Produto\VendaProdutoRepository;
use App\Domain\Repositories\Pagamento\PagamentoRepositoryInterface;
use App\Infra\Persistence\Pagamento\PagamentoRepository;
use App\Domain\Repositories\Pagamento\VendaPagamentoRepositoryInterface;
use App\Infra\Persistence\Pagamento\VendaPagamentoRepository;
use App\Domain\Repositories\Endereco\EnderecoRepositoryInterface;
use App\Infra\Persistence\Endereco\EnderecoRepository;
use App\Domain\Repositories\Cliente\ClienteRepositoryInterface;
use App\Infra\Persistence\Cliente\ClienteRepository;
use App\Domain\Repositories\Cliente\VendaClienteRepositoryInterface;
use App\Infra\Persistence\Cliente\VendaClienteRepository;
use App\Domain\Repositories\Empresa\EmpresaRepositoryInterface;
use App\Infra\Persistence\Empresa\EmpresaRepository;
use App\Domain\Repositories\Emitente\EmitenteRepositoryInterface;
use App\Infra\Persistence\Emitente\EmitenteRepository;
use App\Domain\Repositories\Destinatario\DestinatarioRepositoryInterface;
use App\Infra\Persistence\Destinatario\DestinatarioRepository;

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
                GrupoProdutoRepositoryInterface::class,
                new GrupoProdutoRepository()
            );

        $this->container
            ->set(
                ProdutoRepositoryInterface::class,
                new ProdutoRepository()
            );

        $this->container
            ->set(
                VendaRepositoryInterface::class,
                new VendaRepository()
            );

        $this->container
            ->set(
                VendaProdutoRepositoryInterface::class,
                new VendaProdutoRepository()
            );

        $this->container
            ->set(
                PagamentoRepositoryInterface::class,
                new PagamentoRepository()
            );

        $this->container
            ->set(
                VendaPagamentoRepositoryInterface::class,
                new VendaPagamentoRepository()
            );

        $this->container
            ->set(
                EnderecoRepositoryInterface::class,
                new EnderecoRepository()
            );

        $this->container
            ->set(
                ClienteRepositoryInterface::class,
                new ClienteRepository()
            );

        $this->container
            ->set(
                VendaClienteRepositoryInterface::class,
                new VendaClienteRepository()
            );

        $this->container
            ->set(
                EmpresaRepositoryInterface::class,
                new EmpresaRepository()
            );

        $this->container
            ->set(
                EmitenteRepositoryInterface::class,
                new EmitenteRepository()
            );

        $this->container
            ->set(
                DestinatarioRepositoryInterface::class,
                new DestinatarioRepository()
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