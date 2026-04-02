<?php

namespace App\Config;

use ReflectionClass;

class Container {

    private $instances = [];

    public function set($key, $value){
        $this->instances[$key] = $value;
    }

    public function get($class){
        if(isset($this->instances[$class])){
            return $this->instances[$class];
        }

        if(class_exists($class)){
            $reflection = new ReflectionClass($class);

            $construtor = $reflection->getConstructor();

            if($construtor == null){
                $this->instances[$class] = new $class;
                return $this->instances[$class];
            }

            $parametros = $construtor->getParameters();
            $dependencias = [];

            foreach($parametros as $parametro){
                $classDependencia = $parametro->getType()->getName();
                $dependencias[] = $this->get($classDependencia);
            }

            $this->instances[$class] = $reflection->newInstanceArgs($dependencias);
            return $this->instances[$class];
        }
    }

}