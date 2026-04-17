<?php

use App\Config\Router;
use App\Config\Auth;
use App\Config\Container;
use App\Config\DependencyProvider;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RecuperarSenha\RecuperarSenhaController;
use App\Http\Controllers\Tributacao\TributacaoController;
use App\Http\Controllers\GrupoProduto\GrupoProdutoController;
use App\Http\Controllers\Produto\ProdutoController;
use App\Http\Controllers\Pagamento\PagamentoController;
use App\Http\Controllers\Endereco\EnderecoController;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Pdv\PdvController;

$router = new Router();
$auth = new Auth();
$container = new Container();
$dependencyProvider = new DependencyProvider($container);
$dependencyProvider->register();

$authController = $container->get(AuthController::class);
$userController = $container->get(UserController::class);
$recuperarSenhaController = $container->get(RecuperarSenhaController::class);
$tributacaoController = $container->get(TributacaoController::class);
$grupoProdutoController = $container->get(GrupoProdutoController::class);
$produtoController = $container->get(ProdutoController::class);
$pagamentoController = $container->get(PagamentoController::class);
$enderecoController = $container->get(EnderecoController::class);
$clienteController = $container->get(ClienteController::class);
$pdvController = $container->get(PdvController::class);

// - Rotas

//autenticacao
$router->create("POST", "/auth", [$authController, 'login'], null);
$router->create("POST", "/google-auth", [$authController, 'loginWithGoogle'], null);
$router->create("GET", "/google-link", [$authController, 'generateGoogleAuthLink'], null);
$router->create("GET", "/me", [$authController, 'profile'], $auth);

//usuarios
$router->create("GET", "/usuarios", [$userController, 'index'], $auth);
$router->create("POST", "/usuarios", [$userController, 'store'], $auth);
$router->create("PUT", "/usuarios/{uuid}", [$userController, 'update'], $auth);
$router->create("PATCH", "/usuarios/{uuid}/password", [$userController, 'updatePassword'], $auth);
$router->create("POST", "/usuarios/{uuid}/icon", [$userController, 'updateIcon'], $auth);
$router->create("DELETE", "/usuarios/{uuid}", [$userController, 'destroy'], $auth);

//recuperar-senha   
$router->create("POST", "/recuperar-senha/enviar-codigo", [$recuperarSenhaController, 'sendVerificationCode'], null);
$router->create("PUT", "/recuperar-senha", [$recuperarSenhaController, 'changePassword'], null);

//tributacoes
$router->create("GET", "/tributacoes", [$tributacaoController, 'index'], $auth);
$router->create("POST", "/tributacoes", [$tributacaoController, 'store'], $auth);
$router->create("PUT", "/tributacoes/{uuid}", [$tributacaoController, 'update'], $auth);
$router->create("DELETE", "/tributacoes/{uuid}", [$tributacaoController, 'destroy'], $auth);

//grupo-produto
$router->create("GET", "/grupo-produto", [$grupoProdutoController, 'index'], $auth);
$router->create("POST", "/grupo-produto", [$grupoProdutoController, 'store'], $auth);
$router->create("PUT", "/grupo-produto/{uuid}", [$grupoProdutoController, 'update'], $auth);
$router->create("DELETE", "/grupo-produto/{uuid}", [$grupoProdutoController, 'destroy'], $auth);

//produtos
$router->create("GET", "/produtos", [$produtoController, 'index'], $auth);
$router->create("POST", "/produtos", [$produtoController, 'store'], $auth);
$router->create("PUT", "/produtos", [$produtoController, 'update'], $auth);
$router->create("DELETE", "/produtos", [$produtoController, 'destroy'], $auth);

//formas de pagamento
$router->create("GET", "/pagamentos", [$pagamentoController, 'index'], $auth);
$router->create("POST", "/pagamentos", [$pagamentoController, 'store'], $auth);
$router->create("PUT", "/pagamentos", [$pagamentoController, 'update'], $auth);
$router->create("DELETE", "/pagamentos", [$pagamentoController, 'destroy'], $auth);

//enderecos
$router->create("GET", "/enderecos", [$enderecoController, 'index'], $auth);
$router->create("POST", "/enderecos", [$enderecoController, 'store'], $auth);
$router->create("PUT", "/enderecos", [$enderecoController, 'update'], $auth);
$router->create("DELETE", "/enderecos", [$enderecoController, 'destroy'], $auth);

//clientes
$router->create("GET", "/clientes", [$clienteController, 'index'], $auth);
$router->create("POST", "/clientes", [$clienteController, 'store'], $auth);
$router->create("PUT", "/clientes", [$clienteController, 'update'], $auth);
$router->create("DELETE", "/clientes", [$clienteController, 'destroy'], $auth);

//PDV
$router->create("GET", "/pdv", [$pdvController, 'index'], null);
$router->create("POST", "/pdv", [$pdvController, 'addProductInSale'], null);
$router->create("PUT", "/pdv", [$pdvController, 'updateProductInSale'], null);
$router->create("DELETE", "/pdv", [$pdvController, 'removeProductInSale'], null);
$router->create("DELETE", "/pdv/remove-all", [$pdvController, 'removeAllProductsInSale'], null);
$router->create("POST", "/pdv/vincular-cliente", [$pdvController, 'bindClientOnSale'], null);
// $router->create("DELETE", "/pdv/desvincular-cliente", [$pdvController, 'unlinkClientOnSale'], null);
$router->create("PUT", "/pdv/pagamento", [$pdvController, 'setPaymentMethod'], null);
$router->create("PUT", "/pdv/finalizar", [$pdvController, 'finish'], null);

return $router;