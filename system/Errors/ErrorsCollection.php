<?php

namespace System\Errors;

use System\Helpers\Collection;

class ErrorsCollection extends Collection {
    protected $default_code = 0;

    function addError($code=0,$text=false) : ErrorInterface {
        $error = new ErrorItem(code: $code, text: $text);
        return parent::add($error);
    }
    function setDefault(int $code = 0, string $text=null) : ErrorInterface {
        $this->default_code = $code;
        return $this->addError($code,$text);
    }


    function findError(int $code){
        $default = null;
        foreach ($this as $item){
            if ($item->getCode()==$code){
                return $item;
            }
            if ($item->getCode()==0){
                $default = $item;
            }
        }
        return $default;
    }


}