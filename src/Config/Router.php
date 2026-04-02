<?php

namespace App\Config;

use App\Config\Auth;
use App\Http\Request\Request;
use App\Http\Request\Response;

class Router {

    protected $routers = [];
    protected $auth = null;
    protected $user;

    public function __construct(){
        $this->user = new Auth();
    }

    public function create(string $method, string $path, callable $callback, Auth $auth = null){
        $normalizedPath = $this->normalizePath($path);

        $this->routers[$method][$normalizedPath] = [
            'callback' => $callback,
            'auth' => $auth
        ];
    }

    public function init(){
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $request = new Request();

        $normalizedRequestUri = $this->normalizePath($requestUri);
        $normalizedRequestUri = rtrim($normalizedRequestUri, '/');

        if(isset($this->routers[$httpMethod])){
            foreach($this->routers[$httpMethod] as $path => $route){
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $path);
                $pattern = '/^' . str_replace('/', '\/', $pattern) . '\/?$/';

                if(preg_match($pattern, $normalizedRequestUri, $matches)){
                    if(!is_null($route['auth']) && !$route['auth']->check()){
                        return Response::respJson(['error' => 'Usuário não está logado'], 500);
                    }

                    array_shift($matches);
                    return call_user_func_array($route['callback'], array_merge([$request], $matches));
                }
            }
        }

        Response::respJson(['error' => 'Rota não encontrada'], 404);
    }

    private function normalizePath($path){
        return rtrim(parse_url($path, PHP_URL_PATH), '/');
    }

}