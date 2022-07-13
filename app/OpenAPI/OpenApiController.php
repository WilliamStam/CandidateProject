<?php


namespace App\OpenAPI;

use App\Config;
use App\Profiler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use System\Utilities\Strings;


class OpenApiController {

    public function __invoke(Request $request, Response $response) {
        echo file_get_contents(Strings::fixDirSlashes(realpath(__DIR__)."/swagger.html"));
        exit();

    }
    public function api(Request $request, Response $response){
        echo file_get_contents(Strings::fixDirSlashes(realpath(__DIR__) . "/api.yaml"));
        exit();
    }

}

