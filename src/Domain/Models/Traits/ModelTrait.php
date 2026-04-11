<?php

namespace App\Domain\Models\Traits;

trait ModelTrait {

    function setFields(array $data) : void {
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                if($key === 'senha'){
                    $this->$key = password_hash($value, PASSWORD_BCRYPT);
                    continue;
                }

                $this->$key = $value ?? null;
            }
        }
    }

    function generateUUID() : string {

        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        $data[8] = chr(ord($data) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    function getTable() : string {
        return self::TABLE;
    }

}