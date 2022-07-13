<?php


namespace App\Pages\Controllers;

use App\Config;
use App\Domain\Log\Repositories\LogsRepository;
use App\Domain\Log\Schemas\DetailsSchema;
use App\Domain\Log\Schemas\ListSchema;
use App\Messages;
use App\Profiler;
use App\Request;
use App\Responders\JsonResponder;
use App\Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use System\Pagination\PaginationSchema;


class TriggerErrorsController {

    function __construct(
        protected JsonResponder $responder,
    ) {
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response) {
        $errors = array();
        $data = array();

        // yeah yeah switch statements are anti patterns...
        switch ($request->getAttribute("PARAM.code")){
            case "400":
                throw new HttpBadRequestException($request,"im throwing a 400");
                break;
            case "401":
                throw new HttpUnauthorizedException($request,"im throwing a 401");
                break;
            case "403":
                throw new HttpForbiddenException($request,"im throwing a 403");
                break;
            case "404":
                throw new HttpNotFoundException($request,"im throwing a 404");
                break;
        }
        throw new \Exception("this should trigger the default error handler");


    }


}

