<?php

namespace App\Tables;

use System\Validation\Rules;

class ServicesTable extends AbstractTable {
    const CREATED_AT = null;
    const UPDATED_AT = null;
    public $fillable = ["id", "service"];
    protected $table = 'services';
    protected $rules = array(
        'id' => array(),
        'service' => array(
            [Rules\MaxLength::class, 100]
        )
    );
    protected $casts = array();
}