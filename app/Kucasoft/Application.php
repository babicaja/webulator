<?php

namespace Kucasoft;

use DI\Container;
use Kucasoft\Exceptions\ContainerResolveException;
use Psr\Container\ContainerInterface;

class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Application constructor.
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = ($container) ? : $this->createDefaultContainer();
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

            throw new ContainerResolveException("${identifier} could not be resolved out of the container",$e->getCode(),$e);
        }
    }

    /**
     * Creates a DI\Container as a default option if no other Container is passed in the constructor.
     *
     * @return Container
     */
    private function createDefaultContainer()
    {
        return new Container();
    }
}