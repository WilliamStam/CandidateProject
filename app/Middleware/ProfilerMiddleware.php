<?php

namespace App\Middleware;

use App\Config;
use App\Profiler;
use App\Request;
use App\Response;
use App\Events;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class ProfilerMiddleware {
    public function __construct(
        protected Config $config,
        protected Profiler $profiler,
        protected StreamFactoryInterface $streamFactory,
        protected Events $events
    ) {
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $profiler = $this->profiler->start($request->getUri(), "Route");
        $response = $handler->handle($request);
        $profiler->stop();

        if ($this->config->get("dev")) {
            $body = (string)$response->getBody();

            $profiler_return = array(
                "url" => $request->getUri()->getPath(),
                "method" => $request->getMethod(),
                "items" => $this->profiler->toArray(),
                "total" => array(
                    "time" => $this->profiler->getTotalTime(),
                    "memory" => $this->profiler->getTotalMemory(),
                )
            );

            // if its a json response. returns false if its not a json
            $result = json_decode($body, true);
            if (is_array($result)) {
                $result['PROFILER'] = $profiler_return;
                $body = json_encode($result, JSON_PRETTY_PRINT);
            } else {
                $body = str_replace("@@PROFILER@@", base64_encode(json_encode($profiler_return, JSON_PRETTY_PRINT)), $response->getBody());
            }

            $response = $response->withBody($this->streamFactory->createStream($body));

        }
        $this->events->emit("app.end");
        return $response;
    }
}