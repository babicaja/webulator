<?php

namespace Webulator\HTTP\Controllers;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Request;
use Webulator\Contracts\Response;

abstract class BaseController
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * BaseController constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get the request instance.
     *
     * @return Request
     */
    public function request()
    {
        return $this->container->get(Request::class);
    }

    /**
     * Get the response instance.
     *
     * @return Response
     */
    public function response()
    {
        return $this->container->get(Response::class);
    }
}