<?php

namespace System\Validation\Rules;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

use function count;
use function get_object_vars;
use function is_array;
use function is_int;
use function is_object;
use function is_string;
use function mb_detect_encoding;
use function mb_strlen;

class MinLength extends AbstractValidator implements ValidatorInterface {


     function __construct(protected $key = "",protected $length = 0) {}

    public function check($value): self {

        $length = $this->extractLength($value);

        if ($length < $this->length) {
            $this->addMessage("Too short, must be longer than '{$this->length}' chars");
        }

        return $this;
    }

    private function extractLength($input): ?int {
        if (is_string($input)) {
            return (int)mb_strlen($input, (string)mb_detect_encoding($input));
        }

        if (is_array($input) || $input instanceof CountableInterface) {
            return count($input);
        }

        if (is_object($input)) {
            return $this->extractLength(get_object_vars($input));
        }

        if (is_int($input)) {
            return $this->extractLength((string)$input);
        }

        return null;
    }
}