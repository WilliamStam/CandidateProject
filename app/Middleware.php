<?php
namespace App;
use App\Middleware\CorsMiddleware;
use App\Middleware\ErrorMiddleware;
use App\Middleware\InputsMiddleware;
use App\Middleware\ProfilerMiddleware;
use App\Middleware\RenderMiddleware;
use App\Middleware\ReplaceMiddleware;
use App\Middleware\SystemMiddleware;
use App\Middleware\TenantMiddleware;
use App\Middleware\UserMiddleware;
use App\Templater;
use Slim\App;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;


return function (App $app) {
    $container = $app->getContainer();

    $app->add(CorsMiddleware::class);
    $app->add(InputsMiddleware::class);

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();


    $showWhoops = $container->get(Config::class)->get("dev") ?? false;
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == strtolower('XMLHttpRequest')){
        $showWhoops = false;
    }
    if ((bool)$showWhoops) {
        $app->add(new WhoopsMiddleware(array(
            'enable' => true,
            'editor' => function($file, $line) {
                return "http://localhost:8091?message=%file:%line";
            },
        )));
    } else {
        $app->add(ErrorMiddleware::class);
    }

    $app->add(ProfilerMiddleware::class);
};