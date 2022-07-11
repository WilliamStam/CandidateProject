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
use System\Pagination\PaginationSchema;


class LogsController {
    function __construct(
        protected Config $config,
        protected Profiler $profiler,
        protected JsonResponder $responder,
        protected Messages $messages,
        protected LogsRepository $logsRepository,
    ) {
    }


    public function __invoke(Request $request, Response $response): Response {
        $errors = array();
        $data = array();

        $id = $request->getAttribute("PARAMS.log_id");
        if ($id) {
            $details = $this->logsRepository->get($id);
            $data = $details->toSchema(new DetailsSchema());

        } else {
            $data['options'] = array(
                "search" => $request->getAttribute("GET.search") ?? null,
                "level" => $request->getAttribute("GET.level") ?? null,
                "page" => $request->getAttribute("GET.page") ?? 1,
                "paginate" => $request->getAttribute("GET.paginate") ?? 3,
                "order" => $request->getAttribute("GET.order") ?? array("id"=>"desc"),
            );
            $list = $this->logsRepository->list(...$data['options']);

            $data['list'] = $list->toSchema(new ListSchema());
            $data['pagination'] = $list->pagination()->toSchema(new PaginationSchema());
        }

        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }


}

