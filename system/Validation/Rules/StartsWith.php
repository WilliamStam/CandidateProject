<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;



class StartsWith extends AbstractValidator implements ValidatorInterface {


     function __construct(protected $key = "",protected $chars = null) {}

    public function check($value): self {
        $len = strlen($this->chars);

        if (!(substr((string)$value, 0, $len) == $this->chars)) {
            $this->addMessage("Value must start with '{$this->chars}'");

        }
        return $this;
    }

}