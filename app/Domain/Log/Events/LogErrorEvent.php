<?php

namespace App\Domain\Log\Events;

use App\Log;
use League\Event\AbstractEvent;
use League\Event\EventInterface;
use League\Event\ListenerInterface;

class LogErrorEvent extends AbstractEvent implements ListenerInterface {
    function __construct(
        protected Log $log
    ) {
    }

    public function isListener($listener) {
        return $listener === $this;
    }
    function handle(EventInterface $event, \Throwable $exception = null ){
        $this->log->error($exception->getMessage(), array(
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace(),
        ));
    }

}