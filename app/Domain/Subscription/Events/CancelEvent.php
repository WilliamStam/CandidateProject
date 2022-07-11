<?php

namespace App\Domain\Subscription\Events;

use App\Domain\Subscription\Models\SubscriptionModel;
use App\Log;
use App\Messages;
use App\Profiler;
use League\Event\AbstractEvent;
use League\Event\EventInterface;
use League\Event\ListenerInterface;

class CancelEvent extends AbstractEvent implements ListenerInterface {
    function __construct(
        protected Messages $messages,
        protected Profiler $profiler,
        protected Log $log
    ) {
    }

    public function isListener($listener) {
        return $listener === $this;
    }

    function handle(EventInterface $event, SubscriptionModel $subscription = null) {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $subscription->save(array(
            "canceled_at" => date("Y-m-d H:i:s"),
            "is_active" => "0"
        ));
        $this->messages->success("Subscription Charged");
        $this->log->info("Subscription Canceled", array(
            "msisdn" => $subscription->msisdn,
            "service" => $subscription->service_id,
            "uuid" => $subscription->uuid
        ));
        $profiler->stop();
    }

}