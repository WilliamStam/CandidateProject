<?php

namespace App\Tables;

use System\Validation\Rules;

class SubscriptionsTable extends AbstractTable {
    public $incrementing = false;
    public $fillable = [
        "uuid", "msisdn", "service_id", "charged_at", "canceled_at", "is_active", "created_at", "updated_at"
    ];
    protected $table = 'subscriptions';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $rules = array(
        'msisdn' => array(
            Rules\Required::class,
            Rules\isNumber::class,
            [Rules\StartsWith::class, 27],
            [Rules\MinLength::class, 11],
            [Rules\MaxLength::class, 11]
        ),
        'service_id' => array(
            Rules\isNumber::class,
            Rules\Required::class
        ),
        'is_active' => array(
            Rules\isNumber::class
        ),
        'uuid' => array(
            [Rules\MaxLength::class, 36]
        ),
        'charged_at' => array(),
        'canceled_at' => array(),
        'created_at' => array(),
        'updated_at' => array()
    );
    protected $casts = array();
}