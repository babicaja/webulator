<?php

namespace Webulator;

use DI\Container;
use Webulator\Contracts\ErrorHandler;
use Webulator\Contracts\MiddlewareHandler;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;
use Webulator\Contracts\ResponseEmitter;
use Webulator\Contracts\RouteCollection;
use Webulator\Exceptions\ContainerResolveException;

class Application
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $middleware;

    /**
     * @var Response
     */
    private $response;

    /**
     * Set tha application's container manager.
     *
     * @param Container $container
     */
    public function container(Container $container)
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
        $this->withErrorHandling();
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
        try
        {
            return $this->container->get($identifier);
        }
        catch (\Exception $e)
        {
            throw new ContainerResolveException("${identifier} could not be resolved out of the container", $e->getCode(), $e);
        }
    }

    /**
     * Resolve a fresh instance out of the app's container.
     *
     * @param string $identifier
     * @return mixed
     * @throws ContainerResolveException
     */
    public function make(string $identifier)
    {
        try
        {
            return $this->container->make($identifier);
        }
        catch (\Exception $e)
        {
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
     * @throws \Exception
     */
    public function routes()
    {
        return $this->resolve(RouteCollection::class);
    }

    /**
     * @throws \Exception
     */
    private function withErrorHandling()
    {
        $this->resolve(ErrorHandler::class);
    }

    /**
     * Run through all middleware.
     *
     * @throws \Exception
     */
    private function middleware()
    {
        $this->response = $this->resolve(MiddlewareHandler::class)->pass($this->middleware);
    }

    /**
     * Handle request.
     *
     * @throws \Exception
     */
    private function handle()
    {
        $this->response = $this->resolve(RequestHandler::class)->handle($this->resolve(Request::class));
    }

    /**
     * Respond with a emitter.
     *
     * @throws \Exception
     */
    private function respond()
    {
        return $this->resolve(ResponseEmitter::class)->emit($this->response);
    }
}