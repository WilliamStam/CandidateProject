<?php


namespace App\Pages\Controllers;

use App\Config;
use App\Profiler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class OpenApiController {

    function __construct(
        protected Profiler $profiler,
        protected Config $config,
    ) {
    }


    public function __invoke(Request $request, Response $response) {
        $data = array();

        $path = "{$request->getUri()->getScheme()}://{$request->getUri()->getHost()}";
        $data['base'] = $path;


//         $this->system->debug($request->getUri());


        $format = "swagger";
        if (basename($request->getUri()->getPath()) == "api.json") {
            $format = "json";
        }
        if (basename($request->getUri()->getPath()) == "api.yaml") {
            $format = "yaml";
        }

        echo "hi";

//        return match ($format) {
//            "json" => $this->responder->withJson(
//                response: $response,
//                data: $data['spec'],
//            ),
//            "yaml" => $this->responder->withYaml(
//                response: $response,
//                data: $data['spec'],
//            ),
//            default => $this->responder->withTemplate($response, "OpenAPI.php", $data, dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . "Views")
//
//        };

        return $response;

    }

}

