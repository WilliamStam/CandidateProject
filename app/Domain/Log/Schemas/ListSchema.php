<?php

namespace App\Domain\Log\Schemas;

use System\Schema\AbstractSchema;
use System\Schema\SchemaInterface;


class ListSchema extends AbstractSchema implements SchemaInterface {


    /**
     * @return array
     */
    function __invoke() {

        $item = $this->item;

        return array(
            "id" => $item->id,
            "level" => $item->level,
            "log" => $item->log,
            "created_at" => $item->created_at,
        );


    }
}