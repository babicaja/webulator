<?php

namespace Webulator\Middleware;

use Webulator\Contracts\Middleware;
use Webulator\Contracts\Request;
use Webulator\Contracts\Response;

abstract class BaseMiddleware implements Middleware
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * BaseMiddleware constructor.
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->response = $response;
        $this->request = $request;
    }
}