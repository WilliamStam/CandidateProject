<?php

use App\Errors;
use App\Events;
use App\Events\LogEvent2;
use App\Log;
use App\Messages;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\StreamFactory;


return [
    // Application settings
    'PACKAGE' => function () {
        $package = json_decode(file_get_contents("../composer.json"));
        return $package;
    },

    // slim boilerplate
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    },
    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return (new StreamFactory());
    },
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },
    RouteCollectorInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector();
    },


    // Containers
    Errors::class => function (ContainerInterface $container) {
        $errors = new Errors();
        $errors->addError(400);
        $errors->addError(404,"We need to fire our content creation department, its only a hindsight that this resource doesnt exit yet");
        $errors->addError(401,"You know you, but we dont know you, who are you? Please authenticate");
        $errors->addError(403,"NO! Your bribe money isn't enough to get past our authorization guard named Percy");
        $errors->addError(500,"The server blew up. We will send a team of our highly trained hamsters to scour the wreckage and make sense of the situation in due time");

        $errors->setDefault(0,"OMG WHAT DID YOU DO, we dont know either! Please hold while we call the men in black");

        return $errors;
    },

    Events::class => function (ContainerInterface $container) {
        $emitter = new Events();
        return $emitter;
    },

    Messages::class => function (ContainerInterface $container) {
        return new Messages();
    },

    Log::class => function(ContainerInterface $container) {
        $events = $container->get(Events::class);
        return new \System\Loggers\FunctionLogger(function($level,string $message=null,array $context=array()) use ($events){
            $events->emit("log",$level,$message,$context);
        });
    },
//    LogEvent2::class=>DI\autowire(LogEvent2::class)

];