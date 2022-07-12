<?php

namespace App;

use App\Middleware\CorsMiddleware;
use App\Middleware\ErrorMiddleware;
use App\Middleware\InputsMiddleware;
use App\Middleware\ProfilerMiddleware;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;


/**
 * @param App $app
 * @return void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
return function (App $app) {
    $container = $app->getContainer();

    $app->add(CorsMiddleware::class);
    $app->add(InputsMiddleware::class);

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();


    $showWhoops = $container->get(Config::class)->get("dev") ?? false;
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == strtolower('XMLHttpRequest')) {
        $showWhoops = false;
    }
    if ((bool)$showWhoops) {
        $app->add(new WhoopsMiddleware(array(
            'enable' => true,
            'editor' => function ($file, $line) {
                return "http://localhost:8091?message=%file:%line";
            },
        )));
    } else {
        $app->add(ErrorMiddleware::class);
    }

    $app->add(ProfilerMiddleware::class);
};