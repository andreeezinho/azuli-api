<?php

namespace App\Http\Controllers\Traits;

trait ValidatorTrait {

    protected array $data = [];
    protected array $errors = [];

    public function validate(array $data, array $rules){
        $this->data = $data;

        foreach($rules as $field => $ruleSet){
            $rulesArray = explode('|', $ruleSet);

            foreach($rulesArray as $rule){
                $this->applyRules($field, $rule);
            }
        }

        return empty($this->errors) ? $data : null;
    }

    public function getErrors(){
        return $this->errors;
    }

    protected function applyRules($field, $rule){
        if(strpos($rule, ':') !== false){
            [$ruleName, $param] = explode(':', $rule, 2);

            return $this->$ruleName($field, $param);
        }

        return $this->$rule($field);
    } 

    protected function required($field){
        if (!isset($this->data[$field]) || $this->data[$field] === '' || $this->data[$field] === null) {
            $this->errors[$field][] = "O campo $field é obrigatório.";
        }
    }

    protected function min($field, $min){
        if (!isset($this->data[$field])) {
            return;
        }

        $value = $this->data[$field];

        if (is_array($value)) {
            if (count($value) < $min) {
                $this->errors[$field][] = "O campo $field deve ter no mínimo $min itens.";
            }
            return;
        }

        if (is_numeric($value)) {
            if ($value < $min) {
                $this->errors[$field][] = "O campo $field deve ser no mínimo $min.";
            }
        } else {
            if (strlen((string)$value) < $min) {
                $this->errors[$field][] = "O campo $field deve ter no mínimo $min caracteres.";
            }
        }
    }

    protected function max($field, $max){

        $value = $this->data[$field];

        if (is_array($value)) {
            if (count($value) > $max) {
                $this->errors[$field][] = "O campo $field deve ter no máximo $max itens.";
            }
            return;
        }

        if (is_numeric($value)) {
            if ($value > $max) {
                $this->errors[$field][] = "O campo $field deve ser no máximo $max.";
            }
        } else {
            if (strlen((string)$value) > $max) {
                $this->errors[$field][] = "Este campo deve ter no máximo $max caracteres.";
            }
        }
    }

    protected function email($field){
        if (!isset($this->data[$field])) {
            return;
        }

        if (!isset($this->data[$field]) || !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "O campo $field deve ser um endereço de email válido.";
            return;
        }
    }

    protected function int($field){
        if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field][] = "O campo $field deve ser um número inteiro.";
            return;
        }
    }

    protected function float($field){
        if (!filter_var($this->data[$field], FILTER_VALIDATE_FLOAT)) {
            $this->errors[$field][] = "O campo $field deve ser um número inteiro.";
            return;
        }
    }

    protected function string($field){
        if (!isset($this->data[$field]) || !is_string($this->data[$field])) {
            $this->errors[$field][] = "O campo $field deve ser uma string.";
            return;
        }
    }

    protected function date($field){
        if (!isset($this->data[$field]) || strtotime($this->data[$field]) === false) {
            $this->errors[$field][] = "O campo $field deve ser uma data válida.";
            return;
        }
    }

    protected function boolean($field){
        if (!isset($this->data[$field]) || !is_bool($this->data[$field])) {
            $this->errors[$field][] = "O campo $field deve ser um valor booleano.";
            return;
        }
    }

    private function nullable($field){
        if (!isset($this->data[$field]) || is_null($this->data[$field])) {
            return;
        }
    }

}