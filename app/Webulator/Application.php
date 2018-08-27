<?php

namespace Webulator;

use DI\Container;
use Webulator\Contracts\MiddlewareHandler;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;
use Webulator\Exceptions\ContainerResolveException;
use Psr\Container\ContainerInterface;

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
     * Application constructor.
     */
    public function __construct()
    {
        $this->container = $this->container();
    }

    /**
     * Run the application.
     *
     * @throws \Exception
     */
    public function run()
    {
        $this->wire();
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
     * Creates a DI\Container.
     *
     * @return Container
     */
    private function container()
    {
        return new Container();
    }

    /**
     * Resolve building blocks out of the container.
     *
     * @throws \Exception
     */
    private function wire()
    {
        $this->request = $this->resolve(Request::class);
        $this->response = $this->resolve(Response::class);
        $this->middlewareHandler = $this->resolve(MiddlewareHandler::class);
        $this->requestHandler = $this->resolve(RequestHandler::class);
    }

    /**
     * Run through all middleware
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
        $this->response = $this->requestHandler->handle($this->request);
    }

    /**
     * Respond with a emitter.
     */
    private function respond()
    {
        echo $this->response->getBody();
    }
}