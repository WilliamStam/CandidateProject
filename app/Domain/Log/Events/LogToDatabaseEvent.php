<?php

namespace App\Domain\Log\Events;

use App\Domain\Log\Models\LogModel;
use App\Profiler;
use League\Event\AbstractEvent;
use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Psr\Log\LogLevel;

class LogToDatabaseEvent extends AbstractEvent implements ListenerInterface {
    function __construct(
        protected Profiler $profiler,
        protected LogModel $logModel
    ) {
    }

    public function isListener($listener) {
        return $listener === $this;
    }

    function handle(EventInterface $event, $level = null, $message = null, $context = array()) {

//        if (in_array($level, array(
//            LogLevel::EMERGENCY,
//            LogLevel::ALERT,
//            LogLevel::CRITICAL,
//            LogLevel::ERROR,
//            LogLevel::WARNING,
//            LogLevel::NOTICE,
//        ))) {
            $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
            $payload = array(
                "ip" => $_SERVER['REMOTE_ADDR'] ?? null,
                "proxy_ip" => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
                "level" => strtoupper($level),
                "log" => $message,
                "context" => $context,
            );

            $this->logModel->create($payload);
            $profiler->stop();
//        }

    }

}