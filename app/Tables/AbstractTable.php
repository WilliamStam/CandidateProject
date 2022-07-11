<?php

namespace App\Tables;

use System\Schema\SchemaTrait;
use System\Validation\Validator;

abstract class AbstractTable extends \Illuminate\Database\Eloquent\Model {
    use SchemaTrait;

    protected $rules = array();


    public function validate($data) {
        $validator = new Validator($this->rules);
        return $validator->validate($data);

    }





}