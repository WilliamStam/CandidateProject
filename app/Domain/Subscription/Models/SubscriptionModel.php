<?php

namespace App\Domain\Subscription\Models;

use App\Events;
use App\Profiler;
use App\Tables\SubscriptionsTable;
use App\Tables\SubscriptionsView;
use System\Models\AbstractModel;

class SubscriptionModel extends AbstractModel {
    /**
     * @param Profiler $profiler
     * @param Events $events
     */
    public function __construct(
        protected Profiler $profiler,
        protected Events $events
    ) {
        $this->model = new SubscriptionsView();
        $this->saveModel = new SubscriptionsTable();
    }


    /**
     * @param $msisdn
     * @param $service_id
     * @param callable|null $validation
     * @return $this
     */
    function create($msisdn, $service_id, callable $validation = null): SubscriptionModel {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);


        $this->saveModel->validate(array(
            "msisdn" => $msisdn,
            "service_id" => $service_id
        ), $validation);

        $result = $this->saveModel->updateOrCreate(
            array(
                "msisdn" => $msisdn,
                "service_id" => $service_id
            )
        );


        $this->model->uuid = $result->uuid;
        $this->model->msisdn = $result->msisdn;
        $this->model->service_id = $result->service_id;

        if ($result->wasRecentlyCreated) {
            $this->events->emit("subscription.created", $this);
        }

        $profiler->stop();
        return $this;
    }

    /**
     * @return $this
     */
    function charge() {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $this->save(array(
            "charged_at" => date("Y-m-d H:i:s"),
            "canceled_at" =>null,
            "is_active" => "1",
        ));
        $this->events->emit("subscription.charged", $this);
        $profiler->stop();
        return $this;
    }

    /**
     * @param array $values
     * @param callable|null $validation
     * @return $this
     */
    protected function save(array $values = array(), callable $validation = null): SubscriptionModel {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);

        // save is an upsert. so if there isnt a uuid we need to create 1
        $this->saveModel->validate($values, $validation);

        // do the eloquent dance
        $ret = $this->saveModel->updateOrCreate(
            ["uuid" => $this->model->uuid],
            $values
        );

        // set the keys to the values so that we dont have to call the get again.
        foreach ($values as $key => $value) {
            $this->model->$key = $value;
        }

        $profiler->stop();
        return $this;
    }

    /**
     * @return $this|SubscriptionModel
     */
    function cancel() {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $return = $this->save(array(
            "canceled_at" => date("Y-m-d H:i:s"),
            "is_active" => "0",
        ));
        $this->events->emit("subscription.canceled", $return);
        $profiler->stop();
        return $return;
    }


}