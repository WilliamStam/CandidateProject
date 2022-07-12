<?php


namespace App\Domain\Log\Repositories;

use App\Domain\Log\Models\LogModel;
use App\Profiler;
use App\Tables\LogsTable;
use System\Pagination\Pagination;
use System\Repositories\AbstractRepository;
use System\Repositories\RepositoryCollection;


class LogsRepository extends AbstractRepository {
    /**
     * @param Profiler $profiler
     * @param LogModel $logModel
     */
    function __construct(
        protected Profiler $profiler,
        protected LogModel $logModel,
    ) {
    }

    /**
     * @param string|null $search
     * @param string|array $order
     * @param string|array|null $level
     * @param int|false $paginate
     * @param int $page
     * @return RepositoryCollection
     */
    function list(
        ?string $search = null,
        string|array $order = array("id" => "desc"),
        string|array $level = null,
        int|false $paginate = 30,
        int $page = 1,
    ): RepositoryCollection {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $order = $this->orderBy($order, new LogsTable());

        $records = LogsTable::query();
        $records->select("*");
        // TODO: turn level to caps or smalls, but keep to the same caps for both DB / code
        if ($level) {
            if (is_array($level)) {
                $records->whereIn("level", $level);
            } else {
                $records->where("level", "=", $level);
            }
        }
        if ($search) {
            $records->where(function ($item) use ($search) {
                $search = '%' . $search . '%';
                $item->where("id", 'LIKE', $search);
                $item->orWhere("log", 'LIKE', $search);
                $item->orWhere("level", 'LIKE', $search);
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


    /**
     * @param int $id
     * @return LogModel
     */
    function get(int $id) : LogModel {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $records = LogsTable::query();
        $records->where("id", "=", $id);
        $return = $this->logModel->withModel($records->firstOrNew());
        $profiler->stop();
        return $return;
    }


}