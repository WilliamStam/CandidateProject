<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

use function is_nan;
use function is_numeric;

class isNumber extends AbstractValidator implements ValidatorInterface {



    public function check($value) : self {

        if (!preg_match('/^[0-9]+$/', $value)){
             $this->addMessage("Not a number");
        }



        return $this;
    }
}