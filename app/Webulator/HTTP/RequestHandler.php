<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Dispatcher;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler as WebulatorRequestHandler;
use Webulator\Contracts\Response;
use Webulator\Contracts\RouteCollection;

class RequestHandler implements WebulatorRequestHandler
{
    /**
     * Handle request object with optional route data.
     *
     * @param Request $request
     * @param mixed|null $routes
     * @param Dispatcher $dispatcher
     * @return Response
     */
    public function handle(Request $request, RouteCollection $routes, Dispatcher $dispatcher): Response
    {
        $data = $dispatcher->dispatch($request, $routes);

        switch ($data[0]) {
            case 0: // Not found.
                break;
            case 1: // Found.
                break;
            case 3: // Method not allowed.
                break;
            default: // Same as not found.
                break;
        }

        return new \Webulator\HTTP\Response();
    }
}