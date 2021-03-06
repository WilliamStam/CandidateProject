<?php

namespace App\Domain\Service\Models;

use App\Profiler;
use App\Tables\ServicesTable;
use System\Models\AbstractModel;

class ServiceModel extends AbstractModel {
    /**
     * @param Profiler $profiler
     */
    public function __construct(
        protected Profiler $profiler
    ) {
        $this->model = new ServicesTable();
    }

}