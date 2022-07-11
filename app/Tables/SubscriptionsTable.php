<?php
namespace App\Tables;
use System\Validation\Rules;

class SubscriptionsTable extends AbstractTable {
    protected $table = 'subscriptions';
    
    
    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $fillable = ["uuid","msisdn","service_id","charged_at","canceled_at","is_active","created_at","updated_at"];
    protected $rules = array(
        'msisdn' => array(
            Rules\isNumber::class,
            [Rules\StartsWith::class,27],
            [Rules\MinLength::class,11],
            [Rules\MaxLength::class,11]
        ),
        'service_id' => array(
            Rules\isNumber::class
        ),
        'is_active' => array(
            Rules\isNumber::class
        ),
        'uuid' => array(
            [Rules\MaxLength::class,40]
        ),
        'charged_at' => array(
            
        ),
        'canceled_at' => array(
            
        ),
        'created_at' => array(
            
        ),
        'updated_at' => array(
            
        )
    );
    protected $casts = array(

    );
}