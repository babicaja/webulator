<?php

namespace Webulator;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Dispatcher;
use Webulator\Contracts\MiddlewareHandler;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;
use Webulator\Contracts\RouteCollection;
use Webulator\Exceptions\ContainerResolveException;

class Application
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
     * @var array
     */
    private $middleware;

    /**
     * @var MiddlewareHandler
     */
    private $middlewareHandler;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Set tha application's container manager.
     *
     * @param ContainerInterface $container
     */
    public function container(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Run the application.
     *
     * @throws \Exception
     */
    public function run()
    {
        $this->middleware();
        $this->handle();
        $this->respond();
    }

    /**
     * Bind a value to the app's container.
     *
     * @param string $identifier
     * @param $resolver
     */
    public function bind(string $identifier, $resolver)
    {
        $this->container->set($identifier, $resolver);
    }

    /**
     * Resolve a value out of the app's container.
     *
     * @param string $identifier
     * @return mixed
     * @throws \Exception
     */
    public function resolve(string $identifier)
    {
        try {

            return $this->container->get($identifier);

        } catch (\Exception $e) {

            throw new ContainerResolveException("${identifier} could not be resolved out of the container", $e->getCode(), $e);
        }
    }

    /**
     * Assign middleware classes used by MiddlewareManager.
     *
     * @param array $middleware
     */
    public function pipe(array $middleware = [])
    {
        $this->middleware = $middleware;
    }

    /**
     * Provides route collection object.
     *
     * @return RouteCollection
     */
    public function routes()
    {
        return $this->routeCollection;
//        return $this->resolve(RouteCollection::class);
    }

    /**
     * Resolve building blocks out of the container.
     *
     * @throws \Exception
     */
    public function wire()
    {
        $this->request = $this->resolve(Request::class);
        $this->response = $this->resolve(Response::class);
        $this->middlewareHandler = $this->resolve(MiddlewareHandler::class);
        $this->requestHandler = $this->resolve(RequestHandler::class);
        $this->routeCollection = $this->resolve(RouteCollection::class);
        $this->dispatcher = $this->resolve(Dispatcher::class);
    }

    /**
     * Run through all middleware.
     */
    private function middleware()
    {
        $this->response = $this->middlewareHandler->pass($this->middleware);
    }

    /**
     * Handle request.
     */
    private function handle()
    {
        $this->response = $this->requestHandler->handle($this->request, $this->routeCollection, $this->dispatcher);
    }

    /**
     * Respond with a emitter.
     */
    private function respond()
    {
        echo $this->response->getBody();
    }
}