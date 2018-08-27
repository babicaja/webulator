<?php

namespace Kucasoft\Contracts;

interface MiddlewareHandler
{
    /**
     * Cycle through all middleware classes.
     *
     * @param array $middleware
     * @return Response
     */
    public function pass(array $middleware) : Response;
}