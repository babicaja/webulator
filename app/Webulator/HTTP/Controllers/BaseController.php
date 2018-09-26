<?php

namespace Webulator\HTTP\Controllers;

use Webulator\Contracts\Configuration;
use Webulator\Contracts\Logger;
use Webulator\Contracts\Request;
use Webulator\Contracts\Response;
use Webulator\Contracts\RouteCollection;
use Webulator\Contracts\Template;

abstract class BaseController
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var RouteCollection
     */
    protected $route;

    /**
     * @var Template
     */
    protected $template;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * BaseController constructor.
     *
     * @param Configuration $configuration
     * @param Request $request
     * @param Response $response
     * @param RouteCollection $route
     * @param Template $template
     * @param Logger $logger
     */
    public function __construct(Configuration $configuration, Request $request, Response $response, RouteCollection $route, Template $template, Logger $logger)
    {
        $this->configuration = $configuration;
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
        $this->template = $template;
        $this->logger = $logger;
    }
}