<?php
namespace App\Tables;
use System\Validation\Rules;

class SubscriptionsView extends AbstractTable {
    protected $table = 'VIEW_subscriptions';
    
    
    
    public $fillable = ["uuid","msisdn","charged_at","canceled_at","is_active","created_at","updated_at","service_id","service"];
    protected $rules = array(
        'uuid' => array(
            [Rules\MaxLength::class,40]
        ),
        'msisdn' => array(
            [Rules\MaxLength::class,11]
        ),
        'charged_at' => array(
            
        ),
        'canceled_at' => array(
            
        ),
        'is_active' => array(
            
        ),
        'created_at' => array(
            
        ),
        'updated_at' => array(
            
        ),
        'service_id' => array(
            
        ),
        'service' => array(
            [Rules\MaxLength::class,100]
        )
    );
    protected $casts = array(

    );
}