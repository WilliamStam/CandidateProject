<?php
namespace System\Helpers;

use System\Schema\SchemaWrapper;

class Collection implements \IteratorAggregate {
    private $collection = array();

    function __construct(?iterable $iterable=null){
        if ($iterable){
            $this->addFromIterable($iterable);
        }
    }

    function add($obj) {
        $this->collection[] = $obj;
        return $obj;
    }
    function addFromIterable($iterable) {
        foreach ($iterable as $obj){
            $this->collection[] = $obj;
        }
        return $this;
    }

    function getIterator() {
        return new \ArrayIterator($this->collection);
    }

    function count() : int {
        return count($this->collection);
    }
    function isEmpty() : bool {
        return empty($this->collection);
    }

    function first() {
        return $this->collection[0] ?? null;
    }

    function last() {
        return $this->collection[count($this->collection) - 1] ?? null;
    }

    function toArray() : array {
        $return = array();
        foreach ($this->collection as $item) {
            if (is_object($item) && method_exists($item, "toArray")) {
                $return[] = $item->toArray();
            } else {
                $return[] = $item;
            }

        }
        return $return;
    }

    function getCollection() {
        return $this->collection;
    }

    function __call($method, $args) {
        $return = new Collection();
        foreach ($this->collection as $item) {
            $return->add(call_user_func_array(array($item, $method), $args));
        }

        return $return;
    }

    function __get($key) {
        $return = new Collection();
        foreach ($this->collection as $item) {
            $return->add($item->$key);
        }

        return $return;
    }

    function toSchema() : array {
        $return = array();
        foreach ($this->collection as $item) {
            if (!method_exists($item,"toSchema")){
                $item = new SchemaWrapper($item);
            }
            $return[] = $item->toSchema(...func_get_args());
        }
        return $return;
    }
}

