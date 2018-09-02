<?php

namespace Webulator\Middleware;

use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;

class Example extends BaseMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
//        $this->response->getBody()->write("Handled by Example Middleware.");
        return $this->response;
    }
}