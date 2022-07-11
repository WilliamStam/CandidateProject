<?php

namespace System\Repositories;

use System\Helpers\Collection;
use System\Pagination\Pagination;
use System\Schema\SchemaInterface;
use System\Schema\SchemaWrapper;

class RepositoryCollection extends Collection {
    protected $pagination = false;


    function setPagination(Pagination $pagination) : RepositoryCollection {
       $this->pagination = $pagination;

       return $this;
    }
    function pagination() : Pagination|false {
        return $this->pagination;
    }

}