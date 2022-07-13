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
    $app->get("/", OpenAPI\OpenApiController::class);
    $app->get("/api.yaml", [OpenAPI\OpenApiController::class,"api"]);

    $app->group("/api", function (RouteCollectorProxy $group) {
        $group->get("", OpenAPI\OpenApiController::class);

        $group->group("/subscription", function (RouteCollectorProxy $sub) {
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


    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/api/{path:.*}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });


};

