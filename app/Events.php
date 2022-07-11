<?php

namespace App;

use App\Domain\Subscription\Events\ChargeEvent;
use App\Domain\Subscription\Events\CreatedEvent;
use App\Domain\Subscription\Events\CancelEvent;
use App\Domain\Log\Events\LogErrorEvent;
use App\Domain\Log\Events\LogToDatabaseEvent;
use App\Templater;
use Slim\App;


return function (App $app) {
    $container = $app->getContainer();
    $events = $container->get(Events::class);

    $available_events = array(
        "log"=>array(
            LogToDatabaseEvent::class
        ),
        "error"=>array(
            LogErrorEvent::class
        ),
        "app.start" => array(
            function() use ($container){

            }
        ),
        "app.end" => array(
            function() use ($container){
//                $profiler = $container->get(Profiler::class);
//                echo "page.end";
//                echo "<hr><pre>";
//                echo json_encode($profiler->toArray(),JSON_PRETTY_PRINT);
//                echo "</pre>";
//                echo $profiler->getTotalTime();
//                exit();
            }
        ),
        "subscription.created" => array(
            CreatedEvent::class
        ),
        "subscription.charge" => array(
            ChargeEvent::class
        ),
        "subscription.cancel" => array(
            CancelEvent::class
        )
    );


    foreach ($available_events as $alias=>$handlers){
        foreach ($handlers as $handler){
            if (!is_callable($handler)){
                // need to wire in any DI's for the events and inject their dependencies.
                $container->set($alias,\DI\autowire($handler));
                // create a new instance of the event
                $handler = $container->make($alias);
            }

            $events->addListener($alias,$handler);
        }
    }
};