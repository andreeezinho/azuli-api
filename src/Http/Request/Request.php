<?php

namespace App\Http\Request;

class Request{

    protected $method;
    protected $uri;
    protected $queryParams;
    protected $bodyParams;
    protected $fileParams;
    protected $headers;

    public function __construct(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->queryParams = $_GET;
        $this->bodyParams = $_POST;
        $this->fileParams = $_FILES;
    }

    public function getMethod(){
        return $this->method;
    }

    public function getUri() : string {
        return $this->uri;
    }

    public function all() {
        $input = file_get_contents('php://input');
         
        $data = json_decode($input, true);

        $data = match ($this->getMethod()) {
            'GET' => $this->getQueryParams(),
            'POST', 'PUT', 'PATCH', 'DELETE' => $data
        };
        
        return is_array($data) ? $data : [];
    }

    public function getHeaders(string $key) : ?string {
        $headers = getallheaders();
        $tokenHeader = $headers[$key] ?? null;

        if(is_null($tokenHeader)){
            return null;
        }

        $token = str_replace('Bearer ', '', $tokenHeader);

        return $token ?? null;
    }

    public function getQueryParams(){
        return $this->queryParams;
    }

    public function getBodyParams(){
        return $this->bodyParams;
    }

    public function getFileParams(){
        return $this->fileParams;
    }

    public function getParams($key) : ?array {
        return $this->queryParams[$key] ?? $this->bodyParams[$key] ?? null;
    }

}