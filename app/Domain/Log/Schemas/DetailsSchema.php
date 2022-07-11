<?php

namespace App\Domain\Log\Schemas;

use System\Schema\AbstractSchema;
use System\Schema\SchemaInterface;


class DetailsSchema extends AbstractSchema implements SchemaInterface {


    function __invoke() {

        $item = $this->item;

        return array(
            "id" => $item->id,
            "level" => $item->level,
            "log" => $item->log,
            "context" => $item->context ?? array(),
            "created_at" => $item->created_at,
        );


    }
}