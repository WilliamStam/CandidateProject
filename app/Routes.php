<?php

// Define app routes
namespace App;

use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;


/**
 * @param App $app
 * @return void
 */
return function (App $app) {
    $container = $app->getContainer();


    // Application routes
    $app->get("/", Pages\Controllers\OpenApiController::class);

    $app->group("/api", function (RouteCollectorProxy $group) {
        $group->get("", Pages\Controllers\OpenApiController::class);

        $group->group("/subscription[/{msisdn}]", function (RouteCollectorProxy $sub) {
            $sub->get("", Pages\Controllers\SubscriptionController::class);
            $sub->post("", [Pages\Controllers\SubscriptionController::class, "post"]);
            $sub->put("", [Pages\Controllers\SubscriptionController::class, "put"]);
            $sub->delete("", [Pages\Controllers\SubscriptionController::class, "delete"]);
        });

        $group->group("/logs", function (RouteCollectorProxy $sub) {
            $sub->get("[/{log_id}]", Pages\Controllers\LogsController::class);
        });

        $group->group("/errors", function (RouteCollectorProxy $sub) {
            $sub->get("[/{code}]", Pages\Controllers\TriggerErrorsController::class);
        });
    });


    // needed for ajax stuff
    $app->options('/api[/{path:.*}]', function ($request, $response) {
        return $response;
    });


    $app->get('/update', function ($request, $response) {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        chdir(dirname("../update"));

        $cmd = 'php update.php 2>&1';
        while (@ob_end_flush()) ;

        $proc = popen($cmd, 'r');
        while (!feof($proc)) {
            echo fread($proc, 4096);
            @ flush();
        }
        exit();
    });


    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/api/{path:.*}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });


};

