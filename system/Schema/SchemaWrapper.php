<?php
namespace System\Schema;


class SchemaWrapper {
    use SchemaTrait;
    function __construct(protected $item = null) {}

    function __get($key){
        if (!$key){
            return;
        }
        if (property_exists($this->$key) ){
            return $this->$key;
        }
        return  null;
    }
    function __call(string $key, array $arguments) {
        if (method_exists($this, $key)) {
            return call_user_func_array(array($this, $key), $arguments);
        }
    }

}