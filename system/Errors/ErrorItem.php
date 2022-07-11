<?php

namespace System\Errors;

class ErrorItem implements ErrorInterface {
    function __construct(
        protected int $code = 0,
        protected string $text = "Oops an error occurred",
    ) {
    }
    function getCode() : int {
        return $this->code;
    }
    function getText() : string {
        return $this->text;
    }
    function toArray() : array {
        return array(
            "code"=>$this->getCode(),
            "text"=>$this->getText(),
        );
    }

}