<?php

namespace App\Http\Request;

class Response{

    public static function respJson(array $data, int $status = 200) : void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

}