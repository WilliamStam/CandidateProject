<?php

namespace App\Middleware;

use App\Domain\Log\Events\LogErrorEvent;
use App\Errors;
use App\Events;
use App\Profiler;
use App\Request;
use App\Responders\JsonResponder;
use App\Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Throwable;


class ErrorMiddleware {
    /**
     * @param Errors $errors
     * @param Events $events
     * @param Profiler $profiler
     * @param JsonResponder $jsonResponder
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        protected Errors $errors,
        protected Events $events,
        protected Profiler $profiler,
        protected JsonResponder $jsonResponder,
        protected ResponseFactory $responseFactory
    ) {
    }

    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response {

        try {
            $response = $handler->handle($request);
        } catch (Throwable $e) {
            $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);


            // we show the user a hidden error message. defined in container.php
            $error = $this->errors->findError($e->getCode());

            $data = $error->toArray();

            if (!$error->getText()) {
                $data['text'] = $e->getMessage();
            }

            // 0 isnt a valid status code
            $code = $error->getCode() > 0 ? $error->getCode() : 500;

            // emit that an error happened.
            // we pass the exception in so that any listners can do what they want with it. (the unfriendly masked text is ignored)
            $this->events->emit("error", $e);

            $response = $this->jsonResponder->json(
                response: $this->responseFactory->createResponse($code, $error->getText()),
                data: $data,
            );
            $profiler->stop();
        }

        return $response;

    }

}