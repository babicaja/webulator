<?php

namespace Webulator\Middleware;

use Webulator\Contracts\Response;

class Example extends BaseMiddleware
{
    /**
     * Process an incoming server request and return a response.
     *
     * @return Response
     */
    public function process(): Response
    {
//        $this->response->getBody()->write("Handled by Example Middleware.");
        return $this->response;
    }
}