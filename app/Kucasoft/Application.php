<?php

namespace Kucasoft;

use DI\Container;
use Kucasoft\Contracts\Request;
use Kucasoft\Contracts\RequestHandler;
use Kucasoft\Contracts\Response;
use Kucasoft\Exceptions\ContainerResolveException;
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
     * Application constructor.
     *
     * @throws \Exception
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
     * Resolve building blocks out of the container.
     *
     * @throws \Exception
     */
    private function wire()
    {
        $this->request = $this->resolve(Request::class);
        $this->response = $this->resolve(Response::class);
        $this->requestHandler = $this->resolve(RequestHandler::class);
    }

    private function middleware()
    {
    }

    private function handle()
    {
        $this->response = $this->requestHandler->handle($this->request);
    }

    private function respond()
    {
        echo $this->response->getBody();
//        echo "Hello";
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
     * Creates a DI\Container.
     *
     * @return Container
     */
    private function container()
    {
        return new Container();
    }
}