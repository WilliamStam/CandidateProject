<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;


final class InputsMiddleware {

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();


        // TODO: sanitize the inputs
        foreach ((array)$request->getQueryParams() as $key => $value) {
            $request = $request->withAttribute("GET." . $key, $value);
        }
        foreach ((array)$request->getParsedBody() as $key => $value) {
            $request = $request->withAttribute("POST." . $key, $value);
        }

        foreach ((array)$route->getArguments() as $key => $value) {
            $request = $request->withAttribute("PARAMS." . $key, $value);
        }

        $response = $handler->handle($request);
        return $response;
    }
}