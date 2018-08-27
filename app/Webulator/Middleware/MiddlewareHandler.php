<?php

namespace Webulator\Middleware;

use Webulator\Contracts\MiddlewareHandler as WebulatorMiddlewareHandler;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class MiddlewareHandler implements WebulatorMiddlewareHandler
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var RequestHandler
     */
    private $requestHandler;

    /**
     * MiddlewareHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $this->container->get(Request::class);
        $this->response = $this->container->get(Response::class);
        $this->requestHandler = $this->container->get(RequestHandler::class);
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

                $middleware = $this->container->get($className); // Some auto-wiring magic here

                if ($middleware instanceof MiddlewareInterface) {
                    $this->response = call_user_func_array([$middleware, "process"], [$this->request, $this->requestHandler]);
                }
            }

        });

        return $this->response;
    }
}