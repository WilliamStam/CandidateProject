<?php

namespace App\Tables;

use Illuminate\Database\Eloquent\Model;
use System\Schema\SchemaTrait;
use System\Validation\ValidationFailed;
use System\Validation\Validator;

abstract class AbstractTable extends Model {
    use SchemaTrait;

    protected $rules = array();


    public function validate($values, callable $function = null) {
        $validator = new Validator($this->rules);
        $errors = $validator->validate($values, $function);
        if (!empty($errors)) {
            throw new ValidationFailed("Validation failed", 400, errors: $errors);
        }
        return $this;
    }


}