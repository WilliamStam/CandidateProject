<?php

namespace App\Tables;

use System\Validation\Rules;

class LogsTable extends AbstractTable {
    const UPDATED_AT = null;
    public $fillable = ["id", "level", "log", "context", "created_at"];
    protected $table = 'logs';
    protected $rules = array(
        'id' => array(),
        'level' => array(
            [Rules\MaxLength::class, 50]
        ),
        'log' => array(),
        'context' => array(),
        'created_at' => array()
    );
    protected $casts = array(
        'context' => 'array'
    );
}