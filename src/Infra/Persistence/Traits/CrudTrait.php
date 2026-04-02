<?php

namespace App\Infra\Persistence\Traits;

use PDO;

trait CrudTrait {

    public function findAll(array $filters = [], string $order = 'created_at'){
        $sql = "SELECT * FROM " . $this->model->getTable();

        $params = [];
        $conditions = [];
        
        if (!empty($filters)) {
            $conditions = $this->setWhereClause($filters, $params);
            $sql .= " WHERE " . $conditions;
        }

        $sql .= " ORDER BY {$order} ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_CLASS, static::$className);
    }

    public function save($data){
        [$fields, $values] = $this->prepareCreateFields($data);

        $strFields = implode(', ', $fields);

        $sql = "INSERT INTO 
                {$this->model->getTable()}
            SET
                {$strFields}
        ";

        $stmt = $this->conn->prepare($sql);

        $this->prepareBindings($stmt, $values);

        return $stmt->execute();
    }

    public function edit($data, $object){
        if (empty($data) || !$object) {
            return false;
        }

        [$fields, $params] = $this->prepareUpdateFields($data, $object);

        $strFields = implode(', ', $fields);

        $sql = "UPDATE 
                {$this->model->getTable()} 
            SET
                {$strFields}
            WHERE
                id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $this->prepareBindings($stmt, $params);

        $stmt->bindValue(':id', $object->id);

        return $stmt->execute();
    }

    public function destroy(int $id) : bool {
        $sql = "DELETE FROM 
                {$this->model->getTable()} 
            WHERE 
                id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    private function setWhereClause(array $criteria, array &$params): string {
        $conditions = [];

        foreach ($criteria as $field => $value) {
            if ($field === 'nome') {
                $conditions[] = "$field LIKE ?";
                $params[] = "%$value%";
                continue;
            }

            if ($field === 'email') {
                $conditions[] = "$field LIKE ?";
                $params[] = "%$value%";
                continue;
            }

            $conditions[] = "$field = :$field";
            $params[":$field"] = $value;
        }

        return implode(' AND ', $conditions);
    }

    private function prepareCreateFields($data): array {
        $fields = [];
        $params = [];

        foreach (get_object_vars($data) as $key => $value) {
            if ($value !== null) {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        return [$fields, $params];
    }

    private function prepareUpdateFields($data, $object): array {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (property_exists($object, $key) && $key !== 'uuid') {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        return [$fields, $params];
    }

    private function prepareBindings($stmt, array $params): void {
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
    }

}