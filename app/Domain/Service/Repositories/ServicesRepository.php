<?php

namespace App\Domain\Service\Repositories;

use App\Domain\Service\Models\ServiceModel;
use App\Profiler;
use App\Tables\ServicesTable;
use System\Pagination\Pagination;
use System\Repositories\AbstractRepository;
use System\Repositories\RepositoryCollection;

class ServicesRepository extends AbstractRepository {
    function __construct(
        protected Profiler $profiler,
        protected ServiceModel $serviceModel
    ) {
    }

    function list(
        ?string $search = null,
        string|array $order = array("id"=>"desc"),
        int|false $paginate = 30,
        int $page = 1,
    ): RepositoryCollection {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $order = $this->orderBy($order, new ServicesTable());

        $records = ServicesTable::query();
        $records->select("*");

        if ($search) {
            $records->where(function ($item) use ($search) {
                $search = '%' . $search . '%';
                $item->where("service", 'LIKE', $search);
            });
        }
        foreach ($order as $column => $direction) {
            $records->orderBy($column, $direction);
        }

        if ($paginate) {
            $recordCount = $records->count();
            $pagination = new Pagination($paginate, 11);
            $pagination->calculate($recordCount, $page);
            $records->forPage($page, $pagination->records_per_page);
        } else {
            $pagination = false;
        }

        $return = (new RepositoryCollection($records->get()))->setPagination($pagination);
        $profiler->stop();
        return $return;
    }
    function get(int|string|null $id) : ServiceModel {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $records = ServicesTable::query();
        $records->where("id", "=", $id);
        $return = $this->serviceModel->withModel($records->firstOrNew());
        $profiler->stop();
        return $return;
    }

}