<?php

namespace System\Repositories;

abstract class AbstractRepository {
    function orderBy(array|string $order){
        $available = array();
        $args = func_get_args();
        array_shift($args);
        foreach ($args as $arg){
            if (is_string($arg)){
                $available[] = $arg;
            }
            if ($arg instanceof \Illuminate\Database\Eloquent\Model){
                $arg = $arg->getFillable();
            }
            if (is_array($arg)){
                foreach ($arg as $item){
                    $available[] = $item;
                }
            }
        }

        $return = array();
        if (is_string($order)){
            $order_ = array();
            foreach (explode(",", $order) as $item){
                list($column, $direction) = array_merge(explode("|", $item), array("asc"));
                if ($column){
                    $order_[$column] = $direction;
                }
            }
            $order = $order_;
        }
        foreach ($order as $column=>$direction){
            if (!in_array(strtolower($direction),array("asc","desc"))){
                $direction = "asc";
            }
            if (in_array($column,$available)){
                $return[$column] = $direction;
            }

        }
        return $return;
    }
}