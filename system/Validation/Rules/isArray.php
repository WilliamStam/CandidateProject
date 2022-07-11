<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

use function is_nan;
use function is_numeric;

class isArray extends AbstractValidator implements ValidatorInterface {



    public function check($value) : self {

        if (!is_array($value)){
             $this->addMessage("Must be an array");
        }



        return $this;
    }
}