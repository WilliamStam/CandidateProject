<?php

namespace System\Pagination;

use System\Schema\AbstractSchema;
use System\Schema\SchemaInterface;
use System\Utilities\Strings;


class PaginationSchema extends AbstractSchema implements SchemaInterface {


    function __invoke() {

        $item = $this->item;

        return array(
            "page" => $item->page,
            "offset" => $item->offset,
            "current" => $item->current,
            "previous" => $item->previous,
            "next" => $item->next,
            "last" => $item->last,
            "info" => $item->info,
            "records" => $item->records,
            "pages" => $item->pages,
        );


    }
}