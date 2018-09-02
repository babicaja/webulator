<?php

namespace Webulator\Contracts;

interface RequestHandler
{
    /**
     * Handle request object with optional route data.
     *
     * @param Request $request
     * @param mixed|null $routes
     * @param Dispatcher $dispatcher
     * @return Response
     */
    public function handle(Request $request, RouteCollection $routes, Dispatcher $dispatcher) : Response;
}