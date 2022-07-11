<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

class Required extends AbstractValidator implements ValidatorInterface {



    public function check($value) : Required {

        if (!$value || $value==""){
            $this->addMessage("Required");
        }

//        echo "key: {$this->key} | required".PHP_EOL;

        return $this;
    }
}