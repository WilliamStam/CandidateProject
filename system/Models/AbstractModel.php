<?php

namespace System\Models;

use System\Schema\SchemaTrait;

abstract class AbstractModel {
    use SchemaTrait;

    protected $model;
    protected $updates = array();

    function withModel($model): self {
        $obj = clone $this;
        $obj->model = $model;
        return $obj;
    }

    function __get(string $key) {
        if (isset($this->$key)) {
            return $this->$key;
        }
        if (isset($this->model->$key)) {
            return $this->model->$key;
        }
    }

    function __call(string $key, array $arguments) {

        if (method_exists($this->model, $key)) {
            return call_user_func_array(array($this->model, $key), $arguments);
        }
    }

}