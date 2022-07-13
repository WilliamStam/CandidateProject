<?php


namespace App\Pages\Controllers;

use App\Config;
use App\Profiler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @OA\Info(title="My First API", version="0.1")
 */

/**
 * @OA\Get(
 *     path="/api/resource.json",
 *     @OA\Response(response="200", description="An example resource")
 * )
 */

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




//        $openapi = \OpenApi\Generator::scan([LogsController::class]);
//        header('Content-Type: application/x-yaml');
//        echo $openapi->toYaml();


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

