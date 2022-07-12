<?php

namespace App\Domain\Service\Schemas;

use System\Schema\AbstractSchema;
use System\Schema\SchemaInterface;


class ServiceSchema extends AbstractSchema implements SchemaInterface {


    /**
     * @return array
     */
    function __invoke() {

        $item = $this->item;

        return array(
            "id" => $item->id,
            "service" => $item->service,
        );


    }
}