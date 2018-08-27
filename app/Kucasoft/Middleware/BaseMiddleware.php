<?php

namespace Kucasoft\Middleware;

use Kucasoft\Contracts\Response;

class BaseMiddleware
{
    /**
     * @var Response
     */
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}