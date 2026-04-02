<?php

    date_default_timezone_set('America/Sao_Paulo');

    require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    use App\Http\Middlewares\CorsMiddleware;

    CorsMiddleware::handle();

    $router = require 'src/Routers/web.php';

    $router->init();