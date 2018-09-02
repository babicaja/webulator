<?php

namespace Webulator\Router;

use FastRoute\Dispatcher\GroupCountBased;
use Webulator\Contracts\Dispatcher as WebulatorDispatcher;
use Webulator\Contracts\Request;
use Webulator\Contracts\RouteCollection;

class Dispatcher implements WebulatorDispatcher
{
    /**
     * @var GroupCountBased
     */
    private $dispatcher;

    /**
     * Dispatcher constructor.
     *
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->retrieve());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function dispatch(Request $request)
    {
        return $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
    }
}