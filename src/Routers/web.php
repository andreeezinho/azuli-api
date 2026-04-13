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

return $router;