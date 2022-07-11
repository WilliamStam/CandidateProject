<?php
// https://github.com/Respect/Validation/tree/master/library/Rules
namespace System\Validation;

use System\Validation\AbstractValidator;
use System\Validation\ValidatorInterface;

class Validator implements ValidatorInterface {
    protected $errors = array();

    function __construct(
        protected $rules = array()
    ) {
    }

    public function addRules($rules = array()) {
        $this->rules = $rules;
    }

    public function addRule($key, ValidatorInterface $validator) {
        if (!isset($this->rules[$key])) {
            $this->rules[$key] = array();
        }
        $this->rules[$key][] = $validator;
    }

    public function validate(array $data) {
        foreach ($data as $key => $value) {
            foreach ($this->getRules($key) as $rule) {
                $result = $rule->check($value);
                foreach ($result->getMessages() as $message) {
                    if (!isset($this->errors[$key])) {
                        $this->errors[$key] = array();
                    }
                    $this->errors[$key][] = $message;
                }
            }
        }
        return $this->errors;
    }

    function getErrors(){
        return $this->errors;
    }



    protected function getRules($key) {
        $return = array();
        if (isset($this->rules[$key])) {
            foreach ($this->rules[$key] as $validator) {
                if (is_string($validator)) {
                    $validator = new $validator($key);
                }
                if (is_array($validator)) {
                    $parameters = $validator;
                    $validator = array_shift($parameters);
                    $validator = new $validator($key, ...$parameters);
                }

                $return[] = $validator;

            }
        }
//        var_dump($return);

        return $return;

    }


}