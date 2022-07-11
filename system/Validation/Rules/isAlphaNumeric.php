<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

class isAlphaNumeric extends AbstractValidator implements ValidatorInterface {



    public function check($value) : self {

        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $value)){
            $this->addMessage("Must contain alphanumeric characters only");
        }

//        echo "key: {$this->key} | required".PHP_EOL;

        return $this;
    }
}