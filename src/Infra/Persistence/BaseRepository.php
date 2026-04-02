<?php

namespace App\Infra\Persistence;

use App\Config\Database;
use App\Infra\Persistence\Traits\CrudTrait;
use App\Infra\Persistence\Traits\FindTrait;
use App\Infra\Services\Log\LogService;

abstract class BaseRepository {

    use CrudTrait;
    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
    }

    public function all(array $params = []){
        return $this->findAll($params);
    }

    public function create(array $data){
        if(empty($data)){
            return null;
        }

        $model = $this->model->create($data);

        try {
            $create = $this->save($model);

            if(!$create){
                return null;
            }

            return $this->findBy('uuid', $model->uuid);
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function update(array $data, int $id){
        if(empty($data)){
            return null;
        }

        $data = $this->model->create($data);

        $findId = $this->findBy('id', $id);

        if(is_null($findId)){
            return null;
        }

        try {
            $update = $this->edit($data, $findId);

            if(!$update){
                return null;
            }

            return $this->findBy('id', $id);
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function delete(int $id){
        if(is_null($this->findBy('id', $id))){
            return false;
        }

        try {
            return $this->destroy($id);
        } catch (\PDOException $e) {
            LogService::logError($e->getMessage());
            return null;
        }
    }

}