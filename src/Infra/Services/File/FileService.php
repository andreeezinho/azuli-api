<?php

namespace App\Infra\Services\File;

class FileService {

    public function uploadFile($file, string $dir, string $type = "image")  {
        if(empty($file['name']) || empty($file['tmp_name'])){
            return null;
        }

        $verifyMethod = $type.'Verify';

        if(!$this->$verifyMethod($file)){
            return null;
        }

        $root_dir = rtrim($_SERVER['DOCUMENT_ROOT'] . '/public' . $dir, "/") . '/';

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        $original_name = pathinfo($file['name'], PATHINFO_FILENAME);

        $hash_name = uniqid() . "_" . time() . "." . $ext;

        if(!is_dir($root_dir)){
            if(!mkdir($root_dir, 755, true)){
                return null;
            }
        }

        $dir = $root_dir . $hash_name;

        if(move_uploaded_file($file['tmp_name'], $dir)){
            return [
                'original_name' => $original_name,
                'hash_name' =>$hash_name,
                'dir' => $dir
            ];
        }

        return null;
    }

    private function imageVerify($file) : bool {
         $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/svg+xml'
        ];

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        return in_array($mimeType, $allowedTypes, true);
    }

}