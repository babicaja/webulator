<?php

namespace Webulator\Router;

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
        $match = $this->dispatcher->dispatch($request);

        switch ($match->status()) {
            case $match::FOUND:
                return $this->execute($match);
                break;
            case $match::NOT_ALLOWED:
                $match->controller("RouteErrorController");
                $match->action("notAllowed");
                return $this->execute($match);
                break;
            case $match::NOT_FOUND:
            default:
                $match->controller("RouteErrorController");
                $match->action("notFound");
                return $this->execute($match);
                break;
        }
    }

    /**
     * @param Match $match
     * @return mixed
     * @throws \Exception
     */
    private function execute(Match $match)
    {
        $controller = $match->controller();
        $action = $match->action();
        $parameters = $match->parameters();

        $fullControllerName = "\\Webulator\\HTTP\\Controllers" . "\\" . $controller;

        if (!class_exists($fullControllerName))
        {
            throw new \Exception("The controller ${fullControllerName} does not exist.");
        }
        else {

            $resolved = $this->container->get($fullControllerName);

            if (in_array($action, get_class_methods($fullControllerName)))
            {
                $response = call_user_func_array([$resolved, $action], $parameters);
            } else {
                throw new \Exception("The controller ${controller} does not have the ${action} action.");
            }
        }

        return $response;
    }
}