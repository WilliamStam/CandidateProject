<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;


class isEmail extends AbstractValidator implements ValidatorInterface {

    public function check($value) : self {

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
             $this->addMessage("Not a valid email address");
        }
        return $this;
    }
}