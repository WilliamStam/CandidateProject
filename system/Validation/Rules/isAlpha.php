<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

class isAlpha extends AbstractValidator implements ValidatorInterface {



    public function check($value) : self {

        if (!preg_match('/^[a-zA-Z ]+$/', $value)){
            $this->addMessage("Must contain alphabetical characters only");
        }

//        echo "key: {$this->key} | required".PHP_EOL;

        return $this;
    }
}