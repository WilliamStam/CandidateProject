<?php

namespace App\Domain\Subscription\Events;

use App\Domain\Subscription\Models\SubscriptionModel;
use App\Log;
use App\Messages;
use App\Profiler;
use League\Event\AbstractEvent;
use League\Event\EventInterface;
use League\Event\ListenerInterface;

class CreatedEvent extends AbstractEvent implements ListenerInterface {
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
        $this->messages->success("Subscription Created");
        $this->log->info("Subscription created", array(
            "msisdn" => $subscription->msisdn,
            "service" => $subscription->service_id,
            "uuid" => $subscription->uuid
        ));

        $profiler->stop();
    }

}