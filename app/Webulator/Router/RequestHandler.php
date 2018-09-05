<?php

namespace Webulator\Router;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Dispatcher;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler as WebulatorRequestHandler;
use Webulator\Contracts\Response;
use Webulator\Exceptions\ControllerDoesNotExistException;

class RequestHandler implements WebulatorRequestHandler
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

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
     * Loads the container and calls the desired method based on the data from the Match object.
     *
     * @param Match $match
     * @return mixed
     * @throws \Exception
     */
    private function execute(Match $match)
    {
        $controller = $match->controller();
        $action = $match->action();
        $parameters = $match->parameters();

        $resolvedController = $this->resolveController($controller);


        if (in_array($action, get_class_methods($resolvedController)))
        {
            $response = call_user_func_array([$resolvedController, $action], $parameters);
        }
        else
        {
            throw new \Exception("The controller ${controller} does not have the ${action} action.");
        }

        return $response;
    }

    /**
     * Try to resolve the controller out of the container.
     *
     * @param string $controller
     * @return mixed
     * @throws ControllerDoesNotExistException
     */
    private function resolveController($controller)
    {
       $qualifiedName = "\\Webulator\\HTTP\\Controllers\\".$controller;

        if (!class_exists($qualifiedName))
        {
            throw new ControllerDoesNotExistException("The controller ${controller} does not exist.");
        }

        return $this->container->get($qualifiedName);
    }
}