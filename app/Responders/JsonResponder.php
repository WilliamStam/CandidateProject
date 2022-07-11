<?php

namespace App\Responders;

use App\Messages;
use App\Response;
use System\Responders\ResponderInterface;

use \Psr\Http\Message\ResponseInterface;


class JsonResponder implements ResponderInterface {
    protected $data = array();
    protected $errors = array();
    protected $options = 0;

    public function __construct(
        protected Messages $messages
    ) {
    }

    public function json (
        ResponseInterface $response,
        array $data = array(),
        array $errors = array(),
        int $options = 0
    ) : Response {
        $this->data = $data;
        $this->errors = $errors;
        $this->options = $options;
        return $this->withResponse($response);
    }

    public function withResponse (
        ResponseInterface $response
    ): Response {

        $return = array(
//            "status" => $status,
            "data" => $this->data
        );
        if (!$this->messages->isEmpty()) {
            $return['messages'] = $this->messages->toArray();
        }
        if (!empty($this->errors)) {
            $return['errors'] = $this->errors;
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($return, $this->options));

        return $response;

    }


}
