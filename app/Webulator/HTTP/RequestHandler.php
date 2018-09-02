<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Dispatcher;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler as WebulatorRequestHandler;
use Webulator\Contracts\Response;

class RequestHandler implements WebulatorRequestHandler
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var Response
     */
    private $response;

    public function __construct(Dispatcher $dispatcher, Response $response)
    {
        $this->dispatcher = $dispatcher;
        $this->response = $response;
    }

    /**
     * Handle request object with optional route data.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $data = $this->dispatcher->dispatch($request);

        switch ($data[0]) {
            case 0: // Not found.
                $this->response->getBody()->write("Route not found.");
                break;
            case 1: // Found.
                $this->response->getBody()->write("Great stuff, route exists.");
                break;
            case 3: // Method not allowed.
                $this->response->getBody()->write("Almost there, route exists but wrong verb.");
                break;
            default: // Same as not found.
                $this->response->getBody()->write("Route not found, but why am I here.");
                break;
        }

        return $this->response;
    }
}