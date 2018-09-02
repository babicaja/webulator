<?php

namespace Webulator\Router;

use FastRoute\Dispatcher\GroupCountBased;
use Webulator\Contracts\Dispatcher as WebulatorDispatcher;
use Webulator\Contracts\Request;
use Webulator\Contracts\RouteCollection;

class Dispatcher implements WebulatorDispatcher
{

    /**
     * @param Request $request
     * @param RouteCollection $routes
     * @return mixed
     */
    public function dispatch(Request $request, RouteCollection $routes)
    {
        $dispatcher = new GroupCountBased($routes->retrieve());

        return $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
    }
}