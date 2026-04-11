<?php

namespace App\Http\Middlewares;

class CorsMiddleware {

    public static function handle() {
        header('Access-Control-Allow-Origin: ' . $_ENV['PERMITTED_HOST']);
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

}