<?php

namespace App\Domain\Subscription\Schemas;

use System\Schema\AbstractSchema;
use System\Schema\SchemaInterface;


class SubscriptionSchema extends AbstractSchema implements SchemaInterface {


    function __invoke() {

        $item = $this->item;

        return array(
            "uuid" => $item->uuid,
            "msisdn" => $item->msisdn,
            "charged_at" => $item->charged_at,
            "canceled_at" => $item->canceled_at,
            "is_active" => $item->is_active? true : false,
            "created_at" => $item->created_at,
            "updated_at" => $item->updated_at,
            "service_id" => $item->service_id,
            "service" => $item->service,
        );


    }
}