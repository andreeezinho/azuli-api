<?php

namespace App\Infra\Persistence\Traits;

trait FindTrait {

    public function findBy(string $field, mixed $value){
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->model->getTable() . " WHERE $field = :$field"
        );

        $stmt->execute([":$field" => $value]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::$className);
        $result = $stmt->fetch();

        if(empty($result)){
            return null;
        }

        return $result;
    }

}