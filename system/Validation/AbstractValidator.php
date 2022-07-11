<?php

namespace System\Validation;

abstract class AbstractValidator {

    protected $messages = array();

    function __construct(protected $key = "") { }

    public function addMessage(string $message) {
        $this->messages[] = $message;

    }

    public function getMessages() {
        return $this->messages;
    }
}