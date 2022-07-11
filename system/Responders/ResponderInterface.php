<?php

namespace System\Responders;

use \Psr\Http\Message\ResponseInterface;

interface ResponderInterface {
    function withResponse(ResponseInterface $response) : ResponseInterface;
}