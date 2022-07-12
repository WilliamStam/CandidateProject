<?php

namespace App\Domain\Subscription\Events;

use App\Domain\Subscription\Models\SubscriptionModel;
use App\Log;
use App\Messages;
use App\Profiler;
use League\Event\AbstractEvent;
use League\Event\EventInterface;
use League\Event\ListenerInterface;

class CanceledEvent extends AbstractEvent implements ListenerInterface {
    /**
     * @param Messages $messages
     * @param Profiler $profiler
     * @param Log $log
     */
    function __construct(
        protected Messages $messages,
        protected Profiler $profiler,
        protected Log $log
    ) {
    }

    /**
     * @param $listener
     * @return bool
     */
    public function isListener($listener) {
        return $listener === $this;
    }

    /**
     * @param EventInterface $event
     * @param SubscriptionModel|null $subscription
     * @return void
     */
    function handle(EventInterface $event, SubscriptionModel $subscription = null) {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);

        $this->messages->success("Subscription Canceled");
        $this->log->info("Subscription canceled", array(
            "msisdn" => $subscription->msisdn,
            "service" => $subscription->service_id,
            "uuid" => $subscription->uuid,
            "canceled_at" => $subscription->canceled_at
        ));
        $profiler->stop();
    }

}