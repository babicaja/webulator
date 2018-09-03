<?php

namespace Webulator\HTTP;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Dispatcher;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler as WebulatorRequestHandler;
use Webulator\Contracts\Response;

class RequestHandler implements WebulatorRequestHandler
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * RequestHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->dispatcher = $container->get(Dispatcher::class);
        $this->response = $container->get(Response::class);
        $this->container = $container;
    }

    /**
     * Handle request object with optional route data.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function handle(Request $request): Response
    {
        $data = $this->dispatcher->dispatch($request);

        switch ($data[0]) {
            case 0: // Not found.
                $this->response = $this->response->withStatus(404, "Not Found");
                $this->response->getBody()->write("Sorry the page does not exist.");
                break;
            case 1: // Found.
                $this->response = $this->delegate($data[1]);
                break;
            case 3: // Method not allowed.
                $this->response->getBody()->write("Almost there, route exists but wrong verb.");
                break;
            default: // Same as not found.
                $this->response->getBody()->write("Route not found, but why am I here.");
                break;
        }

        return $this->response;
    }

    /**
     * Check and create controller and call adequate action.
     *
     * @param $handler
     * @return Response
     * @throws \Exception
     */
    private function delegate(string $handler)
    {
        $data = explode("@", $handler);
        $controller = $data[0];
        $method = $data[1] ?? 'index';

        $fullControllerName = __NAMESPACE__ . "\\Controllers" . "\\" . $controller;

        if (!class_exists($fullControllerName))
        {
            throw new \Exception("The controller ${controller} does not exist.");
        }
        else {

            $resolved = $this->container->get($fullControllerName);

            if (in_array($method, get_class_methods($fullControllerName)))
            {
                $response = call_user_func([$resolved, $method]);
            } else {
                throw new \Exception("The controller ${controller} does not have the ${method} action.");
            }
        }

        return $response;
    }
}