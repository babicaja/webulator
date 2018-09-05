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
     * @var Match
     */
    private $match;

    /**
     * Dispatcher constructor.
     *
     * @param RouteCollection $routes
     * @param Match $match
     */
    public function __construct(RouteCollection $routes, Match $match)
    {
        $this->dispatcher = new GroupCountBased($routes->retrieve());
        $this->match = $match;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function dispatch(Request $request): Match
    {
        $data = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($data[0]) {
            case $this->dispatcher::NOT_FOUND:
                $this->match->status($this->match::NOT_FOUND);
                break;
            case $this->dispatcher::FOUND:
                $this->populateMatch($data);
                break;
            case $this->dispatcher::METHOD_NOT_ALLOWED:
                $this->match->status($this->match::NOT_ALLOWED);
                break;
            default:
                break;
        }

        return $this->match;
    }

    private function populateMatch($data)
    {
        list($controller, $action, $parameters) = $this->extractData($data);

        $this->match->status($this->match::FOUND);
        $this->match->controller($controller);
        $this->match->action($action);
        $this->match->parameters($parameters);
    }

    private function extractData($data)
    {
        $data = explode("@", $data[1]);
        $controller = $data[0];
        $method = $data[1] ?? 'index';
        $parameters = isset($data[2]) ? $data[2] : [];

        return [$controller, $method, $parameters];
    }
}