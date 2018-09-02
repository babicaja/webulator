<?php

namespace Webulator\Contracts;

interface RouteCollection
{
    /**
     * Sets a GET route.
     *
     * @param string $route
     * @param $handler
     * @return void
     */
    public function get(string $route, $handler);

    /**
     * Sets a POST route.
     *
     * @param string $route
     * @param $handler
     * @return void
     */
    public function post(string $route, $handler);

    /**
     * Sets an arbitrary route.
     *
     * @param string $method
     * @param string $route
     * @param $handler
     * @return void
     */
    public function add(string $method, string $route, $handler);

    /**
     * Return all defined routes.
     *
     * @return mixed
     */
    public function retrieve();
}