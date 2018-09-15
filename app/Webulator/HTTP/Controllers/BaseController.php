<?php

namespace Webulator\HTTP\Controllers;

use Psr\Container\ContainerInterface;
use Webulator\Contracts\Request;
use Webulator\Contracts\Response;
use Webulator\Contracts\Template;

abstract class BaseController
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Template
     */
    protected $template;

    /**
     * BaseController constructor.
     *
     * @param ContainerInterface $container
     * @param Template $template
     */
    public function __construct(ContainerInterface $container, Template $template)
    {
        $this->container = $container;

        $this->template = $template;
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