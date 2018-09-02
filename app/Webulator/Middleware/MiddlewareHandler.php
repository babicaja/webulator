<?php

namespace Webulator\Middleware;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Middleware;
use Webulator\Contracts\MiddlewareHandler as WebulatorMiddlewareHandler;
use Webulator\Contracts\Response;

class MiddlewareHandler implements WebulatorMiddlewareHandler
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * MiddlewareHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Cycle through all middleware classes.
     *
     * @param array $middleware
     * @return Response
     */
    public function pass(array $middleware): Response
    {
        array_walk($middleware, function($className) {

            if (class_exists($className)) {

                $class = $this->container->get($className); // Some auto-wiring magic here

                if ($class instanceof Middleware) {
                    $response = call_user_func([$class, "process"]);
                }
            }

        });

        return isset($response) ? $response : $this->container->get(Response::class);
    }
}