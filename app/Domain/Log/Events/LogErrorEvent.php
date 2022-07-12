<?php

namespace App\Domain\Log\Events;

use App\Log;
use League\Event\AbstractEvent;
use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Throwable;

class LogErrorEvent extends AbstractEvent implements ListenerInterface {
    /**
     * @param Log $log
     */
    function __construct(
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
     * @param Throwable|null $exception
     * @return void
     */
    function handle(EventInterface $event, Throwable $exception = null) {
        $this->log->error($exception->getMessage(), array(
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace(),
        ));
    }

}