<?php

namespace Webulator\Middleware;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\MiddlewareHandler as WebulatorMiddlewareHandler;
use Webulator\Contracts\Response;

class MiddlewareHandler implements WebulatorMiddlewareHandler
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $middlewareNamespace;

    /**
     * MiddlewareHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->middlewareNamespace = $container->get(Configuration::class)->get("middleware.namespace", "");
    }

    /**
     * Cycle through all middleware classes.
     *
     * @param array $middleware
     * @return Response
     */
    public function pass(array $middleware): Response
    {
        $response = null;

        foreach ($middleware as $m)
        {
            if (class_exists($m)) {

                $class = $this->container->get($m); // Some auto-wiring magic here

                if (in_array("process", get_class_methods($class)))
                {
                    $response = call_user_func([$class, "process"]);
                }
            }
        };

        return $response ? : $this->container->get(Response::class);
    }
}