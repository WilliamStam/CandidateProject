<?php

namespace App\Domain\Subscription\Repositories;

use App\Domain\Subscription\Models\SubscriptionModel;
use App\Profiler;
use App\Tables\SubscriptionsView;
use System\Pagination\Pagination;
use System\Repositories\AbstractRepository;
use System\Repositories\RepositoryCollection;

class SubscriptionRepository extends AbstractRepository {
    /**
     * @param Profiler $profiler
     * @param SubscriptionModel $subscriptionModel
     */
    function __construct(
        protected Profiler $profiler,
        protected SubscriptionModel $subscriptionModel
    ) {
    }

    /**
     * @param string|null $search
     * @param string|array $order
     * @param string|array|null $level
     * @param int|false $paginate
     * @param int $page
     * @param int|string|null $service
     * @param int|string|null $msisdn
     * @return RepositoryCollection
     */
    function list(
        ?string $search = null,
        string|array $order = array("id" => "desc"),
        string|array $level = null,
        int|false $paginate = 30,
        int $page = 1,
        int|string|null $service = null,
        int|string|null $msisdn = null,
    ): RepositoryCollection {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $order = $this->orderBy($order, new SubscriptionsView());

        $records = SubscriptionsView::query();
        $records->select("*");

        if ($search) {
            $records->where(function ($item) use ($search) {
                $search = '%' . $search . '%';
                $item->where("uuid", 'LIKE', $search);
                $item->orWhere("msisdn", 'LIKE', $search);
                $item->orWhere("service", 'LIKE', $search);
            });
        }

        if ($service) {
            $records->where("service_id", "=", $service);
        }
        if ($msisdn) {
            $records->where("msisdn", "=", $msisdn);
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
     * @param int|string|null $service
     * @param int|string|null $msisdn
     * @return SubscriptionModel
     */
    function get(
        int|string|null $service = null,
        int|string|null $msisdn = null,
    ): SubscriptionModel {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $records = SubscriptionsView::query();
        $records->where("service_id", "=", $service);
        $records->where("msisdn", "=", $msisdn);

        $return = $this->subscriptionModel->withModel($records->firstOrNew());

        $profiler->stop();
        return $return;
    }
}