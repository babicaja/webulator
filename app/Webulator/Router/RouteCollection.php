<?php

namespace Webulator\Router;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Webulator\Contracts\RouteCollection as WebulatorRouteCollection;

class RouteCollection implements WebulatorRouteCollection
{
    /**
     * @var RouteCollector
     */
    private $collector;

    public function __construct()
    {
        // The RouteCollector and its dependencies are coupled on purpose so that the DI container is not
        // polluted by this concrete implementation.
        $collector = new RouteCollector(new Std(), new GroupCountBased());
        $this->collector = $collector;
    }

    /**
     * Sets a GET route.
     *
     * @param string $route
     * @param $handler
     * @return void
     */
    public function get(string $route, $handler)
    {
        $this->collector->get($route, $handler);
    }

    /**
     * Sets a POST route.
     *
     * @param string $route
     * @param $handler
     * @return void
     */
    public function post(string $route, $handler)
    {
        $this->collector->post($route, $handler);
    }

    /**
     * Sets an arbitrary route.
     *
     * @param string $method
     * @param string $route
     * @param $handler
     * @return void
     */
    public function add(string $method, string $route, $handler)
    {
        $this->collector->addRoute($method, $route, $handler);
    }

    /**
     * Return all defined routes.
     *
     * @return mixed
     */
    public function retrieve()
    {
        return $this->collector->getData();
    }
}