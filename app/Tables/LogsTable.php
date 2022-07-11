<?php
namespace App\Tables;
use System\Validation\Rules;

class LogsTable extends AbstractTable {
    protected $table = 'logs';
    
    const UPDATED_AT = null;
    
    public $fillable = ["id","level","log","context","created_at"];
    protected $rules = array(
        'id' => array(
            
        ),
        'level' => array(
            [Rules\MaxLength::class,50]
        ),
        'log' => array(
            
        ),
        'context' => array(
            
        ),
        'created_at' => array(
            
        )
    );
    protected $casts = array(
        'context' => 'array'
    );
}