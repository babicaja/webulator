<?php

namespace Webulator\Contracts;

interface Dispatcher
{
    /**
     * @param Request $request
     * @param RouteCollection $routes
     * @return mixed
     */
    public function dispatch(Request $request, RouteCollection $routes);
}
