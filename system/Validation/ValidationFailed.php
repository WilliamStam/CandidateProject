<?php

namespace System\Validation;

use http\Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class ValidationFailed extends \RuntimeException {
    protected $errors = array();

    public function __construct(string $message = "", int $code = 0, array $errors = array() ) {
        $this->errors = $errors;

        parent::__construct($message, $code);
    }
    public function getErrors() : array {
        return $this->errors;
    }


}